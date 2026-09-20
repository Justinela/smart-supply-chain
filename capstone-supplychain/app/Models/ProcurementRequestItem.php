<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_request_id',
        'product_id',
        'quantity_requested',
        'estimated_unit_cost',
    ];

    protected $casts = [
        'estimated_unit_cost' => 'decimal:2',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
