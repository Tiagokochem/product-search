<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Services\ProductService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSearch extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public string $search = '';

    #[Url(keep: true)]
    public array $selectedCategories = [];

    #[Url(keep: true)]
    public array $selectedBrands = [];

    #[Url(keep: true)]
    public string $sortBy = 'name';

    #[Url(keep: true)]
    public string $sortDirection = 'asc';

    #[Url(keep: true)]
    public ?float $minPrice = null;

    #[Url(keep: true)]
    public ?float $maxPrice = null;

    #[Url(keep: true)]
    public bool $inStockOnly = false;

    public bool $showAllBrands = false;

    public int $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategories' => ['except' => []],
        'selectedBrands' => ['except' => []],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected ProductService $productService;

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedSelectedBrands()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedSortDirection()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->selectedBrands = [];
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->inStockOnly = false;
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function updatedInStockOnly()
    {
        $this->resetPage();
    }

    public function toggleSortDirection()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_values(
                array_filter($this->selectedCategories, fn($id) => $id != $categoryId)
            );
        } else {
            $this->selectedCategories[] = $categoryId;
        }
        $this->resetPage();
    }

    public function toggleBrand($brandId)
    {
        if (in_array($brandId, $this->selectedBrands)) {
            $this->selectedBrands = array_values(
                array_filter($this->selectedBrands, fn($id) => $id != $brandId)
            );
        } else {
            $this->selectedBrands[] = $brandId;
        }
        $this->resetPage();
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
            'categories' => $this->selectedCategories,
            'brands' => $this->selectedBrands,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
            'in_stock_only' => $this->inStockOnly,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ];

        $products = $this->productService->searchProducts($filters, $this->perPage);
        
        // Cache categories and brands for 1 hour
        $categories = \Cache::remember('active_categories', 3600, function () {
            return Category::where('active', true)->orderBy('name')->get();
        });
        
        $brands = \Cache::remember('active_brands', 3600, function () {
            return Brand::where('active', true)->orderBy('name')->get();
        });

        return view('livewire.product-search', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
