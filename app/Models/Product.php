<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sku',
        'stock_quantity',
        'images',
        'category_id',
        'brand_id',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'images' => 'array',
        'active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        
        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeActive($query)
    {
        return $query->where('products.active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        $searchTerm = strtolower(trim($search));
        
        return $query->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(sku) LIKE ?', ["%{$searchTerm}%"]);
        });
    }

    public function scopeByCategory($query, $categoryIds)
    {
        if (empty($categoryIds)) {
            return $query;
        }
        
        return $query->whereIn('category_id', $categoryIds);
    }

    public function scopeByBrand($query, $brandIds)
    {
        if (empty($brandIds)) {
            return $query;
        }
        
        return $query->whereIn('brand_id', $brandIds);
    }
}
