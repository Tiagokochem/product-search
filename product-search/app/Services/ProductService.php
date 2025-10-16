<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function searchProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['category', 'brand'])
            ->active();

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply category filter
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $query->byCategory($filters['categories']);
        }

        // Apply brand filter
        if (!empty($filters['brands']) && is_array($filters['brands'])) {
            $query->byBrand($filters['brands']);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        
        match ($sortBy) {
            'price' => $query->orderBy('price', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'stock' => $query->orderBy('stock_quantity', $sortDirection),
            default => $query->orderBy('name', $sortDirection),
        };

        return $query->paginate($perPage);
    }

    public function getActiveCategories()
    {
        return Category::active()
            ->withCount(['products' => function ($query) {
                $query->where('active', true);
            }])
            ->orderBy('name')
            ->get()
            ->filter(function ($category) {
                return $category->products_count > 0;
            });
    }

    public function getActiveBrands()
    {
        return Brand::active()
            ->withCount(['products' => function ($query) {
                $query->where('active', true);
            }])
            ->orderBy('name')
            ->get()
            ->filter(function ($brand) {
                return $brand->products_count > 0;
            });
    }

    public function getFilterCounts(array $filters = []): array
    {
        $baseQuery = Product::query()->active();

        // Apply search filter to base query
        if (!empty($filters['search'])) {
            $baseQuery->search($filters['search']);
        }

        // Count products by category (excluding current category filter)
        $categoryQuery = clone $baseQuery;
        if (!empty($filters['brands']) && is_array($filters['brands'])) {
            $categoryQuery->byBrand($filters['brands']);
        }
        
        $categoryCounts = $categoryQuery
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.active', true)
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('categories.id, categories.name, COUNT(*) as count')
            ->pluck('count', 'id')
            ->toArray();

        // Count products by brand (excluding current brand filter)
        $brandQuery = clone $baseQuery;
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $brandQuery->byCategory($filters['categories']);
        }
        
        $brandCounts = $brandQuery
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('brands.active', true)
            ->groupBy('brands.id', 'brands.name')
            ->selectRaw('brands.id, brands.name, COUNT(*) as count')
            ->pluck('count', 'id')
            ->toArray();

        return [
            'categories' => $categoryCounts,
            'brands' => $brandCounts,
        ];
    }
}
