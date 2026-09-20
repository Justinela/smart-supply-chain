<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPerformance extends Model
{
    use HasFactory;

    protected $table = 'supplier_performance';

    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'on_time_delivery',
        'quality_rating',
        'fulfillment_rate_percent',
        'notes',
    ];

    protected $casts = [
        'on_time_delivery' => 'boolean',
        'fulfillment_rate_percent' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
