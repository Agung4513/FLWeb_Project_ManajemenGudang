<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function getDashboardRoute()
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard',
            'staff' => 'staff.dashboard',
            'supplier' => 'supplier.dashboard',
            default => 'login',
        };
    }
}
