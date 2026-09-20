<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'storage_location_id',
        'quantity_on_hand',
        'quantity_reserved',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }

    public function availableQuantity(): int
    {
        return max(0, $this->quantity_on_hand - $this->quantity_reserved);
    }
}
