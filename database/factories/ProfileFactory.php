<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'image' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['inactive', 'pending', 'active']),
        ];
    }
}
