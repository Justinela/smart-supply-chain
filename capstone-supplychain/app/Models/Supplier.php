<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_id',
        'rating_score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating_score' => 'decimal:2',
    ];

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function performances()
    {
        return $this->hasMany(SupplierPerformance::class);
    }
}
