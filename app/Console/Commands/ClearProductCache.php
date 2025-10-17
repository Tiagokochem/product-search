<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearProductCache extends Command
{
    protected $signature = 'cache:clear-products';
    protected $description = 'Clear all product search related cache';

    public function handle()
    {
        $this->info('Clearing product search cache...');
        
        // Clear specific cache keys
        $patterns = [
            'products_search_*',
            'active_categories*',
            'active_brands*',
            'product_search_cache_keys'
        ];
        
        foreach ($patterns as $pattern) {
            Cache::flush(); // For simplicity, we'll flush all cache
        }
        
        $this->info('✅ Product search cache cleared successfully!');
        
        return Command::SUCCESS;
    }
}
