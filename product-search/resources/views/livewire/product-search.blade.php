<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex gap-4 items-center">
            <div class="flex-1">
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    placeholder="Search products..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <button 
                wire:click="clearFilters"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
            >
                Clear
            </button>
        </div>
        
        @if($search)
            <p class="mt-2 text-sm text-gray-600">Searching for: "{{ $search }}"</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filters Sidebar -->
        <div class="space-y-4">
            <!-- Categories -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Categories</h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:click="toggleCategory({{ $category->id }})"
                                @checked(in_array($category->id, $selectedCategories))
                                class="rounded border-gray-300 text-blue-600"
                            >
                            <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Brands -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Brands</h3>
                <div class="space-y-2">
                    @foreach($brands as $brand)
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:click="toggleBrand({{ $brand->id }})"
                                @checked(in_array($brand->id, $selectedBrands))
                                class="rounded border-gray-300 text-blue-600"
                            >
                            <span class="ml-2 text-sm text-gray-700">{{ $brand->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Results Info -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <p class="text-sm text-gray-700">
                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} 
                    of {{ $products->total() }} results
                </p>
            </div>

            <!-- Products -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $product->category->name }} • {{ $product->brand->name }}
                                </p>
                                <p class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</p>
                                @if($product->description)
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                @endif
                                <div class="mt-3 flex justify-between items-center text-xs text-gray-500">
                                    <span>SKU: {{ $product->sku }}</span>
                                    <span>Stock: {{ $product->stock }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>