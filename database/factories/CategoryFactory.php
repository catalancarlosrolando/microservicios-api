<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class; // ✅ Indica qué modelo usa

    public function definition()
    {
        $name = $this->faker->word();

        return [
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name),
            'description' => $this->faker->sentence(),
            'color'       => $this->faker->hexColor(),
            'is_active'   => $this->faker->boolean(90),
        ];
    }
}

//
//Tip: Faker tiene métodos específicos para casi todo: nombres, emails, direcciones, texto, números, colores, booleanos, fechas, etc.
//Para valores arbitrarios que no tengan método, podés usar text(), word(), regexify()
