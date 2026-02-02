<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $table = 'product_details';

    protected $fillable = [
        'product_id',
        'room_name',
        'price',
        'status',
        'available_rooms',
        'facilities',
        'description',
        'images',
        'is_active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
