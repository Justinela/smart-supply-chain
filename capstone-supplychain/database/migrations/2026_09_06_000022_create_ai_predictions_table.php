<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->id();
            $table->enum('prediction_type', ['DEMAND_FORECAST', 'WAREHOUSE_ALLOCATION']);
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('storage_location_id')->nullable()->constrained('storage_locations')->nullOnDelete();
            $table->date('forecast_date')->nullable();
            $table->decimal('predicted_quantity', 10, 2)->nullable();
            $table->integer('recommended_reorder_qty')->nullable();
            $table->enum('stockout_risk_level', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->default('LOW');
            $table->string('model_used')->default('Scikit-Learn RandomForestRegressor');
            $table->json('metrics_json')->nullable(); // MAE, RMSE, R2
            $table->json('features_used_json')->nullable();
            $table->text('recommendation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_predictions');
    }
};
