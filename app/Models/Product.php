<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'distance_to_kariadi',
        'whatsapp',
        'description',
        'facilities',
        'google_maps_link',
        'is_active',
        'is_published',
        'images',
        'category',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'facilities' => 'array',
        'images' => 'array',
        'distance_to_kariadi' => 'decimal:2',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
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
