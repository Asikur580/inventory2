<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'img_url', 'name', 'measurement_unit', 'brand_id', 'category_id',
        'sale_price', 'discount', 'discount_price', 'weight', 'stock',
        'Variation', 'description', 'purchase_price'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
