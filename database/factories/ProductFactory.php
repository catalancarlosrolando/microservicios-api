<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class; // ✅ Indica qué modelo usa
    public function definition(): array
    {
        return [
            "name"=> $this->faker->name,
            "description"=> $this->faker->paragraph,
            "price"=> $this->faker->randomFloat(2,0,0),
            "stock" => $this->faker->numberBetween(10,20),
        ];
    }
}
