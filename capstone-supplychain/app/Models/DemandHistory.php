<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandHistory extends Model
{
    use HasFactory;

    protected $table = 'demand_history';

    protected $fillable = [
        'product_id',
        'date',
        'quantity_issued',
        'stockouts_recorded',
        'moving_avg_7d',
        'moving_avg_30d',
        'month',
        'day_of_week',
        'is_weekend',
    ];

    protected $casts = [
        'date' => 'date',
        'is_weekend' => 'boolean',
        'moving_avg_7d' => 'decimal:2',
        'moving_avg_30d' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
