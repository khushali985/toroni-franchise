<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RestaurantTable>
 */
class RestaurantTableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'franchise_id' => 1, // will override in test
            'table_no' => $this->faker->numberBetween(1, 50),
            'capacity_people' => 4,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
