<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demand_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->date('date');
            $table->integer('quantity_issued')->default(0);
            $table->integer('stockouts_recorded')->default(0);
            $table->decimal('moving_avg_7d', 10, 2)->default(0.00);
            $table->decimal('moving_avg_30d', 10, 2)->default(0.00);
            $table->integer('month');
            $table->integer('day_of_week');
            $table->boolean('is_weekend')->default(false);
            $table->timestamps();

            $table->unique(['product_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demand_history');
    }
};
