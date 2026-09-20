<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('zone')->default('Zone A');
            $table->string('aisle')->default('Aisle 1');
            $table->string('rack')->default('Rack 01');
            $table->string('shelf')->default('Shelf 1');
            $table->decimal('max_weight_kg', 10, 2)->default(500.00);
            $table->decimal('max_volume_m3', 10, 4)->default(5.0000);
            $table->decimal('occupied_volume_m3', 10, 4)->default(0.0000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_locations');
    }
};
