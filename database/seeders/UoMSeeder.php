<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasurement;

class UoMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Weight
            ['name' => 'Kilogram', 'abbreviation' => 'kg', 'type' => 'weight', 'is_active' => true],
            ['name' => 'Gram', 'abbreviation' => 'g', 'type' => 'weight', 'is_active' => true],
            ['name' => 'Milligram', 'abbreviation' => 'mg', 'type' => 'weight', 'is_active' => true],
            ['name' => 'Ton', 'abbreviation' => 'ton', 'type' => 'weight', 'is_active' => true],
            ['name' => 'Pound', 'abbreviation' => 'lb', 'type' => 'weight', 'is_active' => true],
            ['name' => 'Ounce', 'abbreviation' => 'oz', 'type' => 'weight', 'is_active' => true],

            // Length
            ['name' => 'Meter', 'abbreviation' => 'm', 'type' => 'length', 'is_active' => true],
            ['name' => 'Centimeter', 'abbreviation' => 'cm', 'type' => 'length', 'is_active' => true],
            ['name' => 'Millimeter', 'abbreviation' => 'mm', 'type' => 'length', 'is_active' => true],
            ['name' => 'Kilometer', 'abbreviation' => 'km', 'type' => 'length', 'is_active' => true],
            ['name' => 'Inch', 'abbreviation' => 'in', 'type' => 'length', 'is_active' => true],
            ['name' => 'Foot', 'abbreviation' => 'ft', 'type' => 'length', 'is_active' => true],
            ['name' => 'Yard', 'abbreviation' => 'yd', 'type' => 'length', 'is_active' => true],

            // Volume
            ['name' => 'Liter', 'abbreviation' => 'L', 'type' => 'volume', 'is_active' => true],
            ['name' => 'Milliliter', 'abbreviation' => 'ml', 'type' => 'volume', 'is_active' => true],
            ['name' => 'Cubic Meter', 'abbreviation' => 'm³', 'type' => 'volume', 'is_active' => true],
            ['name' => 'Cubic Centimeter', 'abbreviation' => 'cm³', 'type' => 'volume', 'is_active' => true],
            ['name' => 'Gallon', 'abbreviation' => 'gal', 'type' => 'volume', 'is_active' => true],
            ['name' => 'Quart', 'abbreviation' => 'qt', 'type' => 'volume', 'is_active' => true],
            ['name' => 'Pint', 'abbreviation' => 'pt', 'type' => 'volume', 'is_active' => true],

            // Area
            ['name' => 'Square Meter', 'abbreviation' => 'm²', 'type' => 'area', 'is_active' => true],
            ['name' => 'Square Centimeter', 'abbreviation' => 'cm²', 'type' => 'area', 'is_active' => true],
            ['name' => 'Square Kilometer', 'abbreviation' => 'km²', 'type' => 'area', 'is_active' => true],
            ['name' => 'Hectare', 'abbreviation' => 'ha', 'type' => 'area', 'is_active' => true],
            ['name' => 'Acre', 'abbreviation' => 'ac', 'type' => 'area', 'is_active' => true],
            ['name' => 'Square Foot', 'abbreviation' => 'ft²', 'type' => 'area', 'is_active' => true],
            ['name' => 'Square Inch', 'abbreviation' => 'in²', 'type' => 'area', 'is_active' => true],

            // Count/Quantity
            ['name' => 'Piece', 'abbreviation' => 'pcs', 'type' => 'count', 'is_active' => true],
            ['name' => 'Dozen', 'abbreviation' => 'dz', 'type' => 'count', 'is_active' => true],
            ['name' => 'Pack', 'abbreviation' => 'pk', 'type' => 'count', 'is_active' => true],
            ['name' => 'Box', 'abbreviation' => 'box', 'type' => 'count', 'is_active' => true],
            ['name' => 'Carton', 'abbreviation' => 'ctn', 'type' => 'count', 'is_active' => true],
            ['name' => 'Bundle', 'abbreviation' => 'bdl', 'type' => 'count', 'is_active' => true],
            ['name' => 'Roll', 'abbreviation' => 'rl', 'type' => 'count', 'is_active' => true],

            // Time
            ['name' => 'Second', 'abbreviation' => 's', 'type' => 'time', 'is_active' => true],
            ['name' => 'Minute', 'abbreviation' => 'min', 'type' => 'time', 'is_active' => true],
            ['name' => 'Hour', 'abbreviation' => 'h', 'type' => 'time', 'is_active' => true],
            ['name' => 'Day', 'abbreviation' => 'day', 'type' => 'time', 'is_active' => true],
            ['name' => 'Week', 'abbreviation' => 'wk', 'type' => 'time', 'is_active' => true],
            ['name' => 'Month', 'abbreviation' => 'mo', 'type' => 'time', 'is_active' => true],
            ['name' => 'Year', 'abbreviation' => 'yr', 'type' => 'time', 'is_active' => true],

            // Temperature
            ['name' => 'Celsius', 'abbreviation' => '°C', 'type' => 'temperature', 'is_active' => true],
            ['name' => 'Fahrenheit', 'abbreviation' => '°F', 'type' => 'temperature', 'is_active' => true],
            ['name' => 'Kelvin', 'abbreviation' => 'K', 'type' => 'temperature', 'is_active' => true],

            // Electrical
            ['name' => 'Volt', 'abbreviation' => 'V', 'type' => 'electrical', 'is_active' => true],
            ['name' => 'Ampere', 'abbreviation' => 'A', 'type' => 'electrical', 'is_active' => true],
            ['name' => 'Watt', 'abbreviation' => 'W', 'type' => 'electrical', 'is_active' => true],
            ['name' => 'Ohm', 'abbreviation' => 'Ω', 'type' => 'electrical', 'is_active' => true],

            // Other common units
            ['name' => 'Percentage', 'abbreviation' => '%', 'type' => 'percentage', 'is_active' => true],
            ['name' => 'Unit', 'abbreviation' => 'unit', 'type' => 'general', 'is_active' => true],
            ['name' => 'Set', 'abbreviation' => 'set', 'type' => 'general', 'is_active' => true],
        ];

        foreach ($units as $unit) {
            UnitOfMeasurement::create($unit);
        }
    }
}
