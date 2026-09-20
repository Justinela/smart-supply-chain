<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('unit_of_measure')->default('pcs');
            $table->integer('min_stock_level')->default(10);
            $table->integer('max_stock_level')->default(1000);
            $table->integer('reorder_point')->default(20);
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->decimal('weight_kg', 8, 3)->default(0.500);
            $table->decimal('volume_m3', 8, 4)->default(0.0100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
