<?php

namespace Database\Factories;
use App\Models\Order;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Order::class;

    public function definition(): array
    {
        return [
            'client_name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'product_code' => $this->faker->word,
            'final_price' => $this->faker->randomFloat(2, 10, 500),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
