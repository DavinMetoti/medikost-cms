<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductDetail>
 */
class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => 1, // Will be overridden in tests
            'room_name' => 'Room ' . $this->faker->randomLetter(),
            'price' => $this->faker->numberBetween(300000, 1000000),
            'status' => $this->faker->randomElement(['kosong', 'isi']),
            'available_rooms' => $this->faker->numberBetween(0, 5),
            'facilities' => json_encode(['WiFi', 'AC', 'Kamar Mandi Dalam']),
            'description' => $this->faker->sentence(),
            'images' => json_encode([$this->faker->imageUrl()]),
            'is_active' => true,
        ];
    }
}
