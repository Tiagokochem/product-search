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
        // Create cache key based on filters
        $cacheKey = 'products_search_' . md5(serialize($filters) . $perPage);
        
        return \Cache::remember($cacheKey, 300, function () use ($filters, $perPage) {
            $query = Product::query()
                ->with(['category:id,name', 'brand:id,name'])
                ->select(['id', 'name', 'slug', 'description', 'price', 'sku', 'stock_quantity', 'category_id', 'brand_id', 'active', 'created_at'])
                ->active();

            $this->applyFilters($query, $filters);
            $this->applySorting($query, $filters);

            return $query->paginate($perPage);
        });
    }

    private function applyFilters($query, array $filters): void
    {
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

        // Apply price range filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Apply stock filter
        if (!empty($filters['in_stock_only'])) {
            $query->inStock();
        }
    }

    private function applySorting($query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        
        match ($sortBy) {
            'price' => $query->orderBy('price', $sortDirection),
            'name' => $query->orderBy('name', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'stock' => $query->orderBy('stock_quantity', $sortDirection),
            'category' => $query->join('categories', 'products.category_id', '=', 'categories.id')
                               ->orderBy('categories.name', $sortDirection)
                               ->select('products.*'),
            'brand' => $query->join('brands', 'products.brand_id', '=', 'brands.id')
                            ->orderBy('brands.name', $sortDirection)
                            ->select('products.*'),
            default => $query->orderBy('name', $sortDirection),
        };
    }

    public function getActiveCategories()
    {
        return \Cache::remember('active_categories_with_counts', 1800, function () {
            return Category::active()
                ->withCount(['products' => function ($query) {
                    $query->where('active', true);
                }])
                ->orderBy('name')
                ->get()
                ->filter(function ($category) {
                    return $category->products_count > 0;
                });
        });
    }

    public function getActiveBrands()
    {
        return \Cache::remember('active_brands_with_counts', 1800, function () {
            return Brand::active()
                ->withCount(['products' => function ($query) {
                    $query->where('active', true);
                }])
                ->orderBy('name')
                ->get()
                ->filter(function ($brand) {
                    return $brand->products_count > 0;
                });
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
