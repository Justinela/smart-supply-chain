<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'total_capacity_m3',
        'current_occupancy_m3',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_capacity_m3' => 'decimal:2',
        'current_occupancy_m3' => 'decimal:2',
    ];

    public function storageLocations()
    {
        return $this->hasMany(StorageLocation::class);
    }

    public function occupancyPercentage(): float
    {
        if ($this->total_capacity_m3 <= 0) return 0.0;
        return round(($this->current_occupancy_m3 / $this->total_capacity_m3) * 100, 2);
    }

    public function capacityBadgeClass(): string
    {
        $pct = $this->occupancyPercentage();
        if ($pct > 100) return 'bg-dark text-white border border-dark';
        if ($pct >= 91) return 'bg-danger text-white';
        if ($pct >= 81) return 'bg-warning text-dark';
        if ($pct >= 61) return 'bg-info text-white';
        return 'bg-success text-white';
    }

    public function capacityStatusLabel(): string
    {
        $pct = $this->occupancyPercentage();
        if ($pct > 100) return 'OVER CAPACITY (BLOCKED)';
        if ($pct >= 91) return 'CRITICAL (91-100%)';
        if ($pct >= 81) return 'WARNING (81-90%)';
        if ($pct >= 61) return 'MODERATE (61-80%)';
        return 'NORMAL (0-60%)';
    }
}
