<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('store_rating', 2, 1)->default(0);
            $table->integer('store_review_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review_count']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['store_rating', 'store_review_count']);
        });
    }
};
