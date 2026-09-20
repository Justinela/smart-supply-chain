<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiving_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_record_id')->constrained('receiving_records')->cascadeOnDelete();
            $table->foreignId('purchase_order_item_id')->constrained('purchase_order_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity_received');
            $table->integer('quantity_accepted');
            $table->integer('quantity_rejected')->default(0);
            $table->foreignId('storage_location_id')->nullable()->constrained('storage_locations')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_items');
    }
};
