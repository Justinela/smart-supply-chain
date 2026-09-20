<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->boolean('on_time_delivery')->default(true);
            $table->tinyInteger('quality_rating')->default(5); // 1 to 5
            $table->decimal('fulfillment_rate_percent', 5, 2)->default(100.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_performance');
    }
};
