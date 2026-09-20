<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'code',
        'zone',
        'aisle',
        'rack',
        'shelf',
        'max_weight_kg',
        'max_volume_m3',
        'occupied_volume_m3',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_weight_kg' => 'decimal:2',
        'max_volume_m3' => 'decimal:4',
        'occupied_volume_m3' => 'decimal:4',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function availableVolumeM3(): float
    {
        return max(0, $this->max_volume_m3 - $this->occupied_volume_m3);
    }
}
