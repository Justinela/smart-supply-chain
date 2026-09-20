<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_record_id',
        'purchase_order_item_id',
        'product_id',
        'quantity_received',
        'quantity_accepted',
        'quantity_rejected',
        'storage_location_id',
        'notes',
    ];

    public function receivingRecord()
    {
        return $this->belongsTo(ReceivingRecord::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }
}
