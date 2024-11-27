<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * The current password being used by the factory.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'sale_date' => $this->faker->dateTimeThisYear(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'quantity' => fake()->numberBetween(1,4),
            'product_id' => Product::factory()
        ];
    }
}
