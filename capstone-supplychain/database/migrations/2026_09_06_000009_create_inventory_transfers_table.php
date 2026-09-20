<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('source_location_id')->constrained('storage_locations')->cascadeOnDelete();
            $table->foreignId('destination_location_id')->constrained('storage_locations')->cascadeOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['PENDING', 'IN_TRANSIT', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->boolean('ai_recommended')->default(false);
            $table->foreignId('initiated_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transfers');
    }
};
