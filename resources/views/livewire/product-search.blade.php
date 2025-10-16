<div class="space-y-6">
    <!-- Search and Filters Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Search Input -->
            <div class="flex-1 max-w-md">
                <label for="search" class="sr-only">Search products</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        id="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search products..."
                    >
                </div>
            </div>

            <!-- Sort Controls -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select wire:model.live="sortBy" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="name">Name</option>
                        <option value="price">Price</option>
                        <option value="created_at">Date Added</option>
                        <option value="stock">Stock</option>
                    </select>
                </div>
                <button 
                    wire:click="$set('sortDirection', sortDirection === 'asc' ? 'desc' : 'asc')"
                    class="p-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    title="Toggle sort direction"
                >
                    @if($sortDirection === 'asc')
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                    @else
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                        </svg>
                    @endif
                </button>
            </div>

            <!-- Clear Filters -->
            @if($search || count($selectedCategories) > 0 || count($selectedBrands) > 0)
                <button 
                    wire:click="clearFilters"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    Clear Filters
                </button>
            @endif
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Filters -->
        <div class="lg:w-64 space-y-6">
            <!-- Categories Filter -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($categories as $category)
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:click="toggleCategory({{ $category->id }})"
                                @checked(in_array($category->id, $selectedCategories))
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <span class="ml-2 text-sm text-gray-700">
                                {{ $category->name }}
                                <span class="text-gray-500">({{ $category->products_count }})</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Brands Filter -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Brands</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($brands as $brand)
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:click="toggleBrand({{ $brand->id }})"
                                @checked(in_array($brand->id, $selectedBrands))
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <span class="ml-2 text-sm text-gray-700">
                                {{ $brand->name }}
                                <span class="text-gray-500">({{ $brand->products_count }})</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <!-- Results Info -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-700">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} 
                        of {{ $products->total() }} results
                    </p>
                    @if($search)
                        <p class="text-sm text-gray-500">
                            Search: "<span class="font-medium">{{ $search }}</span>"
                        </p>
                    @endif
                </div>
            </div>

            <!-- Loading State -->
            <div wire:loading class="text-center py-8">
                <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-blue-500 bg-blue-100">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" wire:loading.class="opacity-50">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Product Image -->
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                @if($product->images && count($product->images) > 0)
                                    <img 
                                        src="{{ $product->images[0] }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $product->category->name }} • {{ $product->brand->name }}
                                        </p>
                                    </div>
                                    <div class="ml-2 flex-shrink-0">
                                        <span class="text-lg font-bold text-gray-900">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        SKU: {{ $product->sku }}
                                    </span>
                                    <span class="text-xs {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        @if($product->stock_quantity > 0)
                                            {{ $product->stock_quantity }} in stock
                                        @else
                                            Out of stock
                                        @endif
                                    </span>
                                </div>

                                @if($product->description)
                                    <p class="text-xs text-gray-600 mt-2 line-clamp-2">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-12" wire:loading.remove>
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Try adjusting your search or filter criteria.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
