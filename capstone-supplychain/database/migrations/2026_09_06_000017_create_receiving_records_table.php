<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiving_records', function (Blueprint $table) {
            $table->id();
            $table->string('receiving_number')->unique();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('received_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('delivery_receipt_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'DISCREPANCY'])->default('COMPLETED');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_records');
    }
};
