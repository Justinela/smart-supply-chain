<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

      public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $roles = Role::all();
        if ($roles->isEmpty()) {
            try {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                $roles = Role::all();
            } catch (\Throwable $e) {
                $roles = collect([
                    (object)['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator'],
                    (object)['id' => 2, 'name' => 'warehouse_staff', 'display_name' => 'Warehouse Staff'],
                    (object)['id' => 3, 'name' => 'procurement_staff', 'display_name' => 'Procurement Staff'],
                    (object)['id' => 4, 'name' => 'management', 'display_name' => 'Executive Manager'],
                ]);
            }
        }
        return view('auth.register', compact('roles'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|alpha_dash|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:6|confirmed',
            'captcha' => 'required|string',
        ], [
            'captcha.required' => 'Please enter the CAPTCHA verification code.',
            'username.unique' => 'This username is already taken. Please choose another username.',
            'email.unique' => 'This email address is already registered.',
        ]);

        $captchaError = CaptchaController::validate($request->input('captcha'));
        if ($captchaError) {
            return back()->withErrors(['captcha' => $captchaError])->withInput();
        }

        $username = $request->input('username');
        if (empty($username)) {
            $username = Str::slug(explode('@', $request->email)[0], '_');
            // Ensure username uniqueness
            $baseUsername = $username;
            $count = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $count++;
            }
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            AuditLoggerService::log('REGISTER', 'Authentication', null, ['user_id' => $user->id, 'username' => $user->username, 'email' => $user->email], $user->id);

            return redirect()->route('login')->with('success', "Account created successfully! Please sign in with your credentials.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create account: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        $loginInput = $request->input('login', $request->input('email'));

        $request->validate([
            'password' => 'required|string',
            'captcha' => 'required|string',
        ], [
            'captcha.required' => 'Please enter the CAPTCHA human verification code.',
        ]);

        if (empty($loginInput)) {
            return back()->withErrors(['login' => 'Please enter your username or email address.'])->withInput();
        }

        // 1. Verify CAPTCHA Human Security Code
        $captchaError = CaptchaController::validate($request->input('captcha'));
        if ($captchaError) {
            return back()->withErrors(['captcha' => $captchaError])->withInput();
        }

        $throttleKey = Str::lower($loginInput) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'login' => "Too many failed login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('login');
        }

        // 2. Find user by Username OR Email address
        $user = User::where(function ($query) use ($loginInput) {
            $query->where('username', $loginInput)
                  ->orWhere('email', $loginInput);
        })->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->is_active) {
                RateLimiter::hit($throttleKey, 60);
                return back()->withErrors(['login' => 'Your account has been deactivated. Please contact an administrator.'])->withInput();
            }

            RateLimiter::clear($throttleKey);

            if ($user->two_factor_enabled) {
                $otpCode = $user->generateOtpCode();
                $request->session()->put('2fa_user_id', $user->id);
                $request->session()->put('2fa_remember', $request->boolean('remember'));

                $recipient = strtolower($user->email);
                $subject = '🔒 2FA Security Verification Code - Smart Supply Chain';
                $bodyContent = "Hello {$user->name},\n\nA login attempt requires 2FA security verification for your account ({$recipient}).\n\nYour 6-Digit Verification Code: {$otpCode}\n\nPlease enter this code on the 2FA login screen to complete session authorization.\n\nBest regards,\nSmart Supply Chain Security Team";

                try {
                    Mail::raw($bodyContent, function ($message) use ($recipient, $subject) {
                        $message->to($recipient)->subject($subject);
                    });
                } catch (\Throwable $e) {
                    Log::error("2FA Mail Send Error: " . $e->getMessage());
                }

                AuditLoggerService::log('2FA_OTP_SENT', 'Authentication', null, ['user_id' => $user->id, 'username' => $user->username], $user->id);

                return redirect()->route('login.otp')->with('info', "A 6-digit OTP security code has been sent to your email ('{$recipient}'). Please check your inbox.");
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $user->last_login_at = now();
            $user->save();

            AuditLoggerService::log('LOGIN', 'Authentication', null, ['user_id' => $user->id, 'username' => $user->username, 'email' => $user->email], $user->id);

            return redirect()->intended(route('dashboard'))->with('success', "Welcome back, {$user->name}!");
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records. Please check your username/email and password.',
        ])->onlyInput('login');
    }

    public function showOtpForm(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.otp', compact('user'));
    }

    public function verifyOtp(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'otp_code' => 'required|string|size:6',
        ], [
            'otp_code.required' => 'Please enter the 6-digit OTP code.',
            'otp_code.size' => 'OTP code must be exactly 6 digits.',
        ]);

        if (!$user->verifyOtpCode($request->otp_code)) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP code. Please check your code or click resend.'])->withInput();
        }

        $remember = $request->session()->get('2fa_remember', false);
        $request->session()->forget(['2fa_user_id', '2fa_remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        $user->last_login_at = now();
        $user->save();

        AuditLoggerService::log('2FA_VERIFIED_LOGIN', 'Authentication', null, ['user_id' => $user->id, 'username' => $user->username], $user->id);

        return redirect()->intended(route('dashboard'))->with('success', "Security verification successful! Welcome back, {$user->name}.");
    }

    public function resendOtp(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $otpCode = $user->generateOtpCode();
        $recipient = strtolower($user->email);
        $subject = '🔒 2FA Security Verification Code - Smart Supply Chain';
        $bodyContent = "Hello {$user->name},\n\nYou requested a new 2FA security verification code for your account ({$recipient}).\n\nYour New 6-Digit Verification Code: {$otpCode}\n\nPlease enter this code on the 2FA login screen to complete session authorization.\n\nBest regards,\nSmart Supply Chain Security Team";

        try {
            Mail::raw($bodyContent, function ($message) use ($recipient, $subject) {
                $message->to($recipient)->subject($subject);
            });
        } catch (\Throwable $e) {
            Log::error("2FA Resend Mail Send Error: " . $e->getMessage());
        }

        AuditLoggerService::log('2FA_OTP_RESENT', 'Authentication', null, ['user_id' => $user->id], $user->id);

        return back()->with('success', "A new 6-digit OTP security code has been sent to '{$recipient}'! Please check your inbox.");
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLoggerService::log('LOGOUT', 'Authentication', null, null, Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out successfully.');
    }

    public function sendChangePasswordCode(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            $identity = $request->input('identity');
            if ($identity) {
                $user = User::where('email', $identity)
                            ->orWhere('username', $identity)
                            ->first();
            }
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found matching that email address or username.',
            ], 404);
        }

        // Rate Limiting (10 requests per key / IP)
        $throttleKey = 'change-password-otp|' . $user->id . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            AuditLoggerService::log('OTP_RATE_LIMITED', 'Authentication', null, ['user_id' => $user->id, 'ip' => $request->ip()], $user->id);
            return response()->json([
                'success' => false,
                'message' => "Too many verification code requests. Please wait {$seconds} seconds before trying again.",
            ], 429);
        }

        RateLimiter::hit($throttleKey, 30);

        $recipient = strtolower($user->email);
        // Generate 8-character uppercase OTP code
        $resetToken = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        // Store/Update OTP token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $recipient],
            [
                'token' => $resetToken,
                'created_at' => now(),
            ]
        );

        $subject = '🔒 Change Password Security Verification Code - Smart Supply Chain';
        $bodyContent = "Hello {$user->name},\n\nYou requested a password change security verification code for your account ({$recipient}).\n\nYour Temporary Verification Code: {$resetToken}\n\nPlease enter this 8-character code in the Change Password screen to authorize updating your password.\n\nBest regards,\nSmart Supply Chain Security Team";

        $status = 'sent';
        $errorDetails = null;
        try {
            Mail::raw($bodyContent, function ($message) use ($recipient, $subject) {
                $message->to($recipient)->subject($subject);
            });
        } catch (\Throwable $e) {
            $status = 'failed';
            $errorDetails = $e->getMessage();
            Log::error("SMTP Send Error (Change Password): " . $errorDetails);
        }

        AuditLoggerService::log('CHANGE_PASSWORD_CODE_SENT', 'Authentication', null, ['user_id' => $user->id, 'email' => $recipient, 'status' => $status], $user->id);

        if ($status === 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Unable to send OTP email via Gmail SMTP (535 Bad Credentials). Please ensure 2-Step Verification is active on your Google Account and a valid 16-character App Password is used in .env.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "Verification code has been sent to '{$recipient}'. Please check your inbox.",
        ]);
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'token' => 'required|string|size:8',
            'current_password' => Auth::check() ? 'required|string' : 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'identity.required' => 'Please enter your username or email address.',
            'token.required' => 'Please enter the 8-character verification security code.',
            'token.size' => 'The verification code must be exactly 8 characters long.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.confirmed' => 'New password confirmation does not match.',
        ]);

        $identity = $request->input('identity');
        $user = User::where('email', $identity)
                    ->orWhere('username', $identity)
                    ->first();

        if (!$user) {
            $errorMsg = 'No account found matching that username or email address.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => ['identity' => [$errorMsg]]], 422);
            }
            return back()->withErrors(['identity' => $errorMsg])->withInput();
        }

        if (Auth::check()) {
            if ($user->id !== Auth::id()) {
                $user = Auth::user();
            }
            if ($request->filled('current_password') && !Hash::check($request->current_password, $user->password)) {
                $errorMsg = 'Your current password is incorrect.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => ['current_password' => [$errorMsg]]], 422);
                }
                return back()->withErrors(['current_password' => $errorMsg])->withInput();
            }
        }

        // Verify OTP / Token against DB record
        $email = strtolower(trim($user->email));
        $token = strtoupper(trim($request->input('token')));

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$tokenRecord || strtoupper($tokenRecord->token) !== $token) {
            $errorMsg = 'Invalid verification security code. Please check the code or request a new one.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => ['token' => [$errorMsg]]], 422);
            }
            return back()->withErrors(['token' => $errorMsg])->withInput();
        }

        // Verify Expiration (Valid for 60 minutes)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            $errorMsg = 'This verification security code has expired (valid for 60 minutes). Please request a new code.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => ['token' => [$errorMsg]]], 422);
            }
            return back()->withErrors(['token' => $errorMsg])->withInput();
        }

        // Update Password & Delete Token
        $user->password = Hash::make($request->password);
        $user->save();

        if (!Auth::check()) {
            Auth::login($user);
        }

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        AuditLoggerService::log('PASSWORD_CHANGE_COMPLETED', 'Authentication', null, ['user_id' => $user->id, 'username' => $user->username], $user->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully! Select your preferred next step below.',
                'show_choices' => true,
            ]);
        }

        return redirect()->route('password.change')->with('password_changed_options', true)->with('success', 'Password updated successfully!');
    }
}
