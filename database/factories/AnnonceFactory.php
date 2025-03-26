<?php

namespace Database\Factories;

use App\Models\Annonce;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnonceFactory extends Factory
{
    protected $model = Annonce::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['regular', 'featured']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
