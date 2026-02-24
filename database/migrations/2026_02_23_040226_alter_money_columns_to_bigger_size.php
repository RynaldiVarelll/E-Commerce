<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->change();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->change();
        });
    }

    public function down(): void
    {
        //
    }
};
