<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_number',
        'purchase_order_id',
        'received_by_user_id',
        'delivery_receipt_number',
        'invoice_number',
        'status',
        'notes',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    public function items()
    {
        return $this->hasMany(ReceivingItem::class);
    }
}
