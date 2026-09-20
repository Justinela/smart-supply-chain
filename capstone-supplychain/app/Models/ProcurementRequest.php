<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'requested_by_user_id',
        'status',
        'total_estimated_cost',
        'justification',
    ];

    protected $casts = [
        'total_estimated_cost' => 'decimal:2',
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function items()
    {
        return $this->hasMany(ProcurementRequestItem::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
