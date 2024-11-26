<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * The current password being used by the factory.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => fake()->name(),
            'brand' => fake()->company(),
            'quantity' => fake()->numberBetween(1,10),
            'price'=> fake()->randomFloat(1, 10, 100),
            'active'=> fake()->boolean()
        ];
    }
}
