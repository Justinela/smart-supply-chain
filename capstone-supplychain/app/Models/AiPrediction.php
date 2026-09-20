<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'prediction_type',
        'product_id',
        'storage_location_id',
        'forecast_date',
        'predicted_quantity',
        'recommended_reorder_qty',
        'stockout_risk_level',
        'model_used',
        'metrics_json',
        'features_used_json',
        'recommendation_reason',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'predicted_quantity' => 'decimal:2',
        'metrics_json' => 'array',
        'features_used_json' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }
}
