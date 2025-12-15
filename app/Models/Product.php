<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'category_id',
        'supplier_id',
        'tag',
        'description',
        'stock',
        'stock_minimum',
        'price',
        'unit_id',
        'location',
        'is_active',
        'images',
        'specifications',
        'is_published',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'stock_minimum' => 'integer',
        'is_active' => 'boolean',
        'unit' => 'string', // Added unit cast
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_id');
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', '.');
    }

    public function getImageSrcAttribute()
    {
        return $this->images
            ? 'data:image/jpeg;base64,' . $this->images
            : null;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
