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
            // Composite index for search with filters
            $table->index(['active', 'category_id', 'brand_id'], 'products_search_filters_idx');
            
            // Index for price range queries
            $table->index(['active', 'price'], 'products_price_search_idx');
            
            // Index for stock queries
            $table->index(['active', 'stock_quantity'], 'products_stock_search_idx');
            
            // Full-text search index for name and description
            $table->index(['name'], 'products_name_search_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            // Optimize category lookups
            $table->index(['active', 'name'], 'categories_active_name_idx');
        });

        Schema::table('brands', function (Blueprint $table) {
            // Optimize brand lookups
            $table->index(['active', 'name'], 'brands_active_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_search_filters_idx');
            $table->dropIndex('products_price_search_idx');
            $table->dropIndex('products_stock_search_idx');
            $table->dropIndex('products_name_search_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_active_name_idx');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('brands_active_name_idx');
        });
    }
};
