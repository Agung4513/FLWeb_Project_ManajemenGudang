<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number', 'supplier_id', 'manager_id',
        'order_date', 'expected_delivery_date',
        'status', 'notes', 'confirmed_by_supplier_at', 'supplier_notes'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'confirmed_by_supplier_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function items()
    {
        return $this->hasMany(RestockItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
