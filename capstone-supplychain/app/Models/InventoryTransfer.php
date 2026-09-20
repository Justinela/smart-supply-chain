<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_number',
        'product_id',
        'source_location_id',
        'destination_location_id',
        'quantity',
        'status',
        'ai_recommended',
        'initiated_by_user_id',
        'approved_by_user_id',
    ];

    protected $casts = [
        'ai_recommended' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sourceLocation()
    {
        return $this->belongsTo(StorageLocation::class, 'source_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(StorageLocation::class, 'destination_location_id');
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
