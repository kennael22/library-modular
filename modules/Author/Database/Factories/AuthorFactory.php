<?php

namespace Modules\Author\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Author\Models\Author;

class AuthorFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'last_name' => $this->faker->lastName(),
            'suffix_name' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'III', 'IV', 'PhD', 'MD']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
