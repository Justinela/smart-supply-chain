<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\AuditLoggerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Enforce Administrator authorization for all User Management actions.
     */
    protected function authorizeAdmin(): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access: User Management is strictly restricted to Administrators.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::with('role')->latest();

        // 1. Search Filter: partial match on username, full name, email (case-insensitive)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 2. Role Filter: matches by role_id (numeric) or role name/display_name
        $roleParam = $request->input('role_id', $request->input('role'));
        if (!empty($roleParam)) {
            if (is_numeric($roleParam)) {
                $query->where('role_id', $roleParam);
            } else {
                $roleSearch = trim($roleParam);
                $query->whereHas('role', function ($rQ) use ($roleSearch) {
                    $rQ->where('name', $roleSearch)
                       ->orWhere('display_name', $roleSearch);
                    if (strtolower($roleSearch) === 'administrator') {
                        $rQ->orWhere('name', 'admin');
                    } elseif (strtolower($roleSearch) === 'warehouse staff') {
                        $rQ->orWhere('name', 'warehouse_staff');
                    } elseif (strtolower($roleSearch) === 'procurement staff') {
                        $rQ->orWhere('name', 'procurement_staff');
                    } elseif (strtolower($roleSearch) === 'executive manager') {
                        $rQ->orWhere('name', 'management');
                    }
                });
            }
        }

        // 3. Status Filter: active / inactive
        if ($request->filled('status')) {
            $statusVal = strtolower(trim($request->status));
            if ($statusVal === 'active' || $statusVal === '1') {
                $query->where('is_active', true);
            } elseif ($statusVal === 'inactive' || $statusVal === '0') {
                $query->where('is_active', false);
            }
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|alpha_dash|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
            'captcha' => 'required|string',
        ], [
            'captcha.required' => 'Please enter the CAPTCHA verification code.',
            'username.unique' => 'This username is already taken.',
        ]);

        $captchaError = CaptchaController::validate($request->input('captcha'));
        if ($captchaError) {
            return back()->withErrors(['captcha' => $captchaError])->withInput();
        }

        $username = $request->input('username');
        if (empty($username)) {
            $username = Str::slug(explode('@', $request->email)[0], '_');
            $baseUsername = $username;
            $count = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $count++;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLoggerService::log('CREATE_USER', 'UserManagement', null, ['user_id' => $user->id, 'username' => $user->username, 'email' => $user->email, 'role_id' => $user->role_id]);

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' created successfully!");
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'alpha_dash', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required',
        ]);

        $newActiveStatus = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        if ($user->isAdmin() && Auth::id() !== $user->id && User::whereHas('role', fn($q)=>$q->where('name','admin'))->count() <= 1 && !$newActiveStatus) {
            return back()->withErrors(['error' => 'Cannot deactivate the last remaining Administrator.']);
        }

        $oldValues = $user->only(['name', 'username', 'email', 'role_id', 'is_active']);

        $user->name = $request->name;
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->is_active = $newActiveStatus;
        $user->save();

        AuditLoggerService::log('UPDATE_USER', 'UserManagement', $oldValues, $user->only(['name', 'username', 'email', 'role_id', 'is_active']));

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' updated successfully!");
    }

    public function toggleStatus(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own logged-in account.']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'ACTIVATED' : 'DEACTIVATED';
        AuditLoggerService::log('TOGGLE_USER_STATUS', 'UserManagement', null, ['user_id' => $user->id, 'new_status' => $statusStr]);

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' has been {$statusStr}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        AuditLoggerService::log('RESET_USER_PASSWORD', 'UserManagement', null, ['user_id' => $user->id]);

        return redirect()->route('admin.users.index')->with('success', "Password reset successfully for '{$user->name}'.");
    }
}
