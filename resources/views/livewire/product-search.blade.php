<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-lg border border-blue-100 p-6">
        <div class="flex gap-4 items-center">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        placeholder="Buscar produtos..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                </div>
            </div>
            <button 
                wire:click="clearFilters"
                class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all font-medium shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                Limpar
            </button>
        </div>
        
        @if($search)
            <p class="mt-2 text-sm text-gray-600">Buscando por: "{{ $search }}"</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filters Sidebar -->
        <div class="space-y-4">
            <!-- Price Range -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-lg border border-green-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Faixa de Preço
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preço Mínimo</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-green-600 text-sm font-medium">R$</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.500ms="minPrice"
                                placeholder="0,00"
                                step="0.01"
                                min="0"
                                class="w-full pl-10 pr-3 py-2 rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500 focus:ring-1 transition-colors"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preço Máximo</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-green-600 text-sm font-medium">R$</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.500ms="maxPrice"
                                placeholder="999,99"
                                step="0.01"
                                min="0"
                                class="w-full pl-10 pr-3 py-2 rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500 focus:ring-1 transition-colors"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg shadow-lg border border-purple-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5m14 14H5"></path>
                    </svg>
                    Categorias
                </h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                            <input 
                                type="checkbox" 
                                wire:click="toggleCategory({{ $category->id }})"
                                @checked(in_array($category->id, $selectedCategories))
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                            >
                            <span class="ml-3 text-sm text-gray-700 select-none">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Brands -->
            <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-lg shadow-lg border border-orange-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Marcas
                    </h3>
                    <button 
                        wire:click="$toggle('showAllBrands')"
                        class="text-orange-600 hover:text-orange-800 transition-colors p-1 rounded-full hover:bg-orange-100"
                    >
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': showAllBrands}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($brands->take($showAllBrands ? $brands->count() : 5) as $brand)
                        <label class="flex items-center p-2 rounded-md hover:bg-orange-100 cursor-pointer transition-colors">
                            <input 
                                type="checkbox" 
                                wire:click="toggleBrand({{ $brand->id }})"
                                @checked(in_array($brand->id, $selectedBrands))
                                class="rounded border-orange-300 text-orange-600 focus:ring-orange-500 focus:ring-2"
                            >
                            <span class="ml-3 text-sm text-gray-700 select-none">{{ $brand->name }}</span>
                        </label>
                    @endforeach
                    @if($brands->count() > 5 && !$showAllBrands)
                        <button 
                            wire:click="$set('showAllBrands', true)"
                            class="w-full text-center text-sm text-orange-600 hover:text-orange-800 py-2 hover:bg-orange-100 rounded-md transition-colors"
                        >
                            Ver mais {{ $brands->count() - 5 }} marcas...
                        </button>
                    @endif
                </div>
            </div>

            <!-- Stock Filter -->
            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-lg shadow-lg border border-teal-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Disponibilidade
                </h3>
                <label class="flex items-center p-3 rounded-md hover:bg-teal-100 cursor-pointer transition-colors border border-teal-200">
                    <input 
                        type="checkbox" 
                        wire:model.live="inStockOnly"
                        class="rounded border-teal-300 text-teal-600 focus:ring-teal-500 focus:ring-2"
                    >
                    <span class="ml-3 text-sm text-gray-700 select-none font-medium">Apenas em Estoque</span>
                </label>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Sorting and Results Info -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        <!-- Pagination info will be shown by Livewire pagination -->
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-gray-700">Ordenar por:</label>
                        <select 
                            wire:model.live="sortBy" 
                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                            <option value="name">Nome</option>
                            <option value="price">Preço</option>
                            <option value="created_at">Mais Recentes</option>
                            <option value="stock">Estoque</option>
                            <option value="category">Categoria</option>
                            <option value="brand">Marca</option>
                        </select>
                        
                        <button 
                            wire:click="toggleSortDirection"
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                            title="Alterar direção da ordenação"
                        >
                            @if($sortDirection === 'asc')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                </svg>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-200 hover:border-blue-300">
                            <div class="p-6">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $product->category->name }} • {{ $product->brand->name }}
                                </p>
                                <p class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                @if($product->description)
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                @endif
                                <div class="mt-3 flex justify-between items-center text-xs">
                                    <span class="text-gray-500">SKU: {{ $product->sku }}</span>
                                    <span class="flex items-center gap-1">
                                        @if($product->stock_quantity > 0)
                                            <span class="w-2 h-2 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full animate-pulse"></span>
                                            <span class="text-green-600 font-medium">{{ $product->stock_quantity }} em estoque</span>
                                        @else
                                            <span class="w-2 h-2 bg-gradient-to-r from-red-400 to-pink-500 rounded-full"></span>
                                            <span class="text-red-600 font-medium">Fora de estoque</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links('pagination::tailwind') }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-500">Tente ajustar sua busca ou critérios de filtro.</p>
                </div>
            @endif
        </div>
    </div>
</div>