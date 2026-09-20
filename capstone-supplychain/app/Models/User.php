<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'name',
        'username',
        'email',
        'password',
        'is_active',
        'two_factor_enabled',
        'otp_code',
        'otp_expires_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ];
    }

    public function generateOtpCode(): string
    {
        $code = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_code = $code;
        $this->otp_expires_at = now()->addMinutes(5);
        $this->save();
        return $code;
    }

    public function verifyOtpCode(?string $code): bool
    {
        if (empty($code) || empty($this->otp_code) || empty($this->otp_expires_at)) {
            return false;
        }

        if ($this->otp_expires_at->isPast()) {
            return false;
        }

        if (trim($code) === trim($this->otp_code)) {
            $this->clearOtpCode();
            return true;
        }

        return false;
    }

    public function clearOtpCode(): void
    {
        $this->otp_code = null;
        $this->otp_expires_at = null;
        $this->save();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        if (!$this->role) {
            return false;
        }

        $currentName = strtolower(trim($this->role->name));
        $currentDisplay = strtolower(trim($this->role->display_name ?? ''));
        $target = strtolower(trim($roleName));

        if ($currentName === $target || $currentDisplay === $target) {
            return true;
        }

        $execAliases = ['management', 'executive_manager', 'executive-manager', 'executive manager', 'executive', 'manager'];
        if (in_array($target, $execAliases) && (in_array($currentName, $execAliases) || in_array($currentDisplay, $execAliases))) {
            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isWarehouseStaff(): bool
    {
        return $this->hasRole('warehouse_staff') || $this->isAdmin();
    }

    public function isProcurementStaff(): bool
    {
        return $this->hasRole('procurement_staff') || $this->isAdmin();
    }

    public function isManagement(): bool
    {
        return $this->hasRole('management') || $this->isAdmin();
    }

    public function getRoleBadgeClassAttribute(): string
    {
        if (!$this->role) return 'bg-secondary';
        $roleName = strtolower($this->role->name);
        if (in_array($roleName, ['management', 'executive_manager'])) {
            return 'bg-warning text-dark';
        }
        return match ($roleName) {
            'admin' => 'bg-indigo text-white',
            'warehouse_staff' => 'bg-primary text-white',
            'procurement_staff' => 'bg-success text-white',
            default => 'bg-secondary text-white',
        };
    }

    public function getRoleDisplayNameAttribute(): string
    {
        return $this->role->display_name ?? 'User';
    }
}
