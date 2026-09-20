<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'procurement_request_id',
        'supplier_id',
        'created_by_user_id',
        'approved_by_user_id',
        'status',
        'total_amount',
        'order_date',
        'expected_delivery_date',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function receivingRecords()
    {
        return $this->hasMany(ReceivingRecord::class);
    }

    public function documentLinks()
    {
        return $this->morphMany(DocumentLink::class, 'linkable');
    }
}
