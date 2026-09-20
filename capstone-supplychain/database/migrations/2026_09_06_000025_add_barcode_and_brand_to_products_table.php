<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique()->after('sku');
            $table->string('brand')->nullable()->after('name');
            $table->integer('safety_stock')->default(5)->after('reorder_point');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'brand', 'safety_stock']);
        });
    }
};
