<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Kost',
            'address' => $this->faker->address(),
            'distance_to_kariadi' => $this->faker->randomFloat(2, 0.5, 10.0),
            'whatsapp' => $this->faker->phoneNumber(),
            'description' => $this->faker->paragraph(),
            'facilities' => json_encode([
                [
                    'name' => 'Fasilitas Utama',
                    'items' => ['WiFi', 'AC', 'Kamar Mandi Dalam']
                ]
            ]),
            'google_maps_link' => $this->faker->url(),
            'is_active' => true,
            'is_published' => true,
            'images' => json_encode([$this->faker->imageUrl()]),
            'category' => $this->faker->randomElement(['Campur', 'Putri', 'Putra']),
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}
