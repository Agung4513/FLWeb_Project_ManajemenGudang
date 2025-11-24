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
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function restockOrdersAsManager()
    {
        return $this->hasMany(RestockOrder::class, 'manager_id');
    }

    public function restockOrdersAsSupplier()
    {
        return $this->hasMany(RestockOrder::class, 'supplier_id');
    }

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getDashboardRoute()
    {
        return match ($this->role) {
            'admin'     => route('admin.dashboard'),
            'manager'   => route('manager.dashboard'),
            'staff'     => route('staff.dashboard'),
            'supplier'  => route('supplier.dashboard'),
            default     => route('home'),
        };
    }

    public function getDashboardUrl()
    {
        return route($this->getDashboardRoute());
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
