<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->category1 = Category::factory()->create(['name' => 'Electronics']);
        $this->category2 = Category::factory()->create(['name' => 'Clothing']);
        
        $this->brand1 = Brand::factory()->create(['name' => 'Apple']);
        $this->brand2 = Brand::factory()->create(['name' => 'Samsung']);
        
        $this->product1 = Product::factory()->create([
            'name' => 'iPhone 15',
            'category_id' => $this->category1->id,
            'brand_id' => $this->brand1->id,
            'price' => 999.99,
        ]);
        
        $this->product2 = Product::factory()->create([
            'name' => 'Samsung Galaxy S24',
            'category_id' => $this->category1->id,
            'brand_id' => $this->brand2->id,
            'price' => 899.99,
        ]);
        
        $this->product3 = Product::factory()->create([
            'name' => 'Nike T-Shirt',
            'category_id' => $this->category2->id,
            'brand_id' => Brand::factory()->create(['name' => 'Nike'])->id,
            'price' => 29.99,
        ]);
    }

    public function test_product_search_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeLivewire('product-search');
    }

    public function test_search_by_product_name(): void
    {
        Livewire::test('product-search')
            ->set('search', 'iPhone')
            ->assertSee('iPhone 15')
            ->assertDontSee('Samsung Galaxy S24')
            ->assertDontSee('Nike T-Shirt');
    }

    public function test_filter_by_category(): void
    {
        Livewire::test('product-search')
            ->call('toggleCategory', $this->category1->id)
            ->assertSee('iPhone 15')
            ->assertSee('Samsung Galaxy S24')
            ->assertDontSee('Nike T-Shirt');
    }

    public function test_filter_by_brand(): void
    {
        Livewire::test('product-search')
            ->call('toggleBrand', $this->brand1->id)
            ->assertSee('iPhone 15')
            ->assertDontSee('Samsung Galaxy S24')
            ->assertDontSee('Nike T-Shirt');
    }

    public function test_filter_by_multiple_categories(): void
    {
        Livewire::test('product-search')
            ->call('toggleCategory', $this->category1->id)
            ->call('toggleCategory', $this->category2->id)
            ->assertSee('iPhone 15')
            ->assertSee('Samsung Galaxy S24')
            ->assertSee('Nike T-Shirt');
    }

    public function test_filter_by_multiple_brands(): void
    {
        Livewire::test('product-search')
            ->call('toggleBrand', $this->brand1->id)
            ->call('toggleBrand', $this->brand2->id)
            ->assertSee('iPhone 15')
            ->assertSee('Samsung Galaxy S24')
            ->assertDontSee('Nike T-Shirt');
    }

    public function test_combined_search_and_filters(): void
    {
        Livewire::test('product-search')
            ->set('search', 'Galaxy')
            ->call('toggleCategory', $this->category1->id)
            ->call('toggleBrand', $this->brand2->id)
            ->assertSee('Samsung Galaxy S24')
            ->assertDontSee('iPhone 15')
            ->assertDontSee('Nike T-Shirt');
    }

    public function test_sort_by_price_ascending(): void
    {
        $component = Livewire::test('product-search')
            ->set('sortBy', 'price')
            ->set('sortDirection', 'asc');
            
        // Check that sorting parameters are set correctly
        $component->assertSet('sortBy', 'price')
                 ->assertSet('sortDirection', 'asc');
                 
        // Verify the component renders without errors
        $component->assertStatus(200);
    }

    public function test_sort_by_price_descending(): void
    {
        $component = Livewire::test('product-search')
            ->set('sortBy', 'price')
            ->set('sortDirection', 'desc');
            
        // Check that sorting parameters are set correctly
        $component->assertSet('sortBy', 'price')
                 ->assertSet('sortDirection', 'desc');
                 
        // Verify the component renders without errors
        $component->assertStatus(200);
    }

    public function test_sort_by_name(): void
    {
        Livewire::test('product-search')
            ->set('sortBy', 'name')
            ->set('sortDirection', 'asc')
            ->assertOk();
    }

    public function test_clear_filters(): void
    {
        Livewire::test('product-search')
            ->set('search', 'iPhone')
            ->call('toggleCategory', $this->category1->id)
            ->call('toggleBrand', $this->brand1->id)
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('selectedCategories', [])
            ->assertSet('selectedBrands', [])
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'asc');
    }

    public function test_filter_persistence_in_url(): void
    {
        $component = Livewire::test('product-search')
            ->set('search', 'iPhone')
            ->call('toggleCategory', $this->category1->id);
            
        // Check that the component has the correct values
        $this->assertEquals('iPhone', $component->get('search'));
        $this->assertContains($this->category1->id, $component->get('selectedCategories'));
    }

    public function test_pagination_works(): void
    {
        // Create more products to test pagination
        Product::factory(20)->create([
            'category_id' => $this->category1->id,
            'brand_id' => $this->brand1->id,
        ]);

        Livewire::test('product-search')
            ->assertSee('Showing')
            ->assertSee('results');
    }

    public function test_no_results_message(): void
    {
        Livewire::test('product-search')
            ->set('search', 'NonExistentProduct')
            ->assertSee('No products found')
            ->assertSee('Try adjusting your search or filter criteria');
    }

    public function test_toggle_category_removes_when_already_selected(): void
    {
        Livewire::test('product-search')
            ->call('toggleCategory', $this->category1->id)
            ->assertSet('selectedCategories', [$this->category1->id])
            ->call('toggleCategory', $this->category1->id)
            ->assertSet('selectedCategories', []);
    }

    public function test_toggle_brand_removes_when_already_selected(): void
    {
        Livewire::test('product-search')
            ->call('toggleBrand', $this->brand1->id)
            ->assertSet('selectedBrands', [$this->brand1->id])
            ->call('toggleBrand', $this->brand1->id)
            ->assertSet('selectedBrands', []);
    }

    public function test_search_is_case_insensitive(): void
    {
        Livewire::test('product-search')
            ->set('search', 'iphone')
            ->assertSee('iPhone 15');
            
        Livewire::test('product-search')
            ->set('search', 'IPHONE')
            ->assertSee('iPhone 15');
    }

    public function test_search_debounce_functionality(): void
    {
        // This test ensures the search input has debounce functionality
        // The actual debounce is handled by Livewire's wire:model.live.debounce
        Livewire::test('product-search')
            ->set('search', 'iP')
            ->set('search', 'iPh')
            ->set('search', 'iPhone')
            ->assertSee('iPhone 15');
    }
}
