<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'barcode',
        'name',
        'brand',
        'unit_of_measure',
        'min_stock_level',
        'max_stock_level',
        'reorder_point',
        'safety_stock',
        'unit_cost',
        'weight_kg',
        'volume_m3',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'unit_cost' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'volume_m3' => 'decimal:4',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function totalQuantityOnHand(): int
    {
        return $this->inventories()->sum('quantity_on_hand');
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function demandHistories()
    {
        return $this->hasMany(DemandHistory::class);
    }

    public function aiPredictions()
    {
        return $this->hasMany(AiPrediction::class);
    }
}
