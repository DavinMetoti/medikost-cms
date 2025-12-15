<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    protected $table = 'unit_of_measurements';

    protected $fillable = [
        'name',
        'abbreviation',
        'type',
        'is_active',
    ];

    public static function getActiveUnits()
    {
        return self::where('is_active', true)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id', 'id');
    }
}
