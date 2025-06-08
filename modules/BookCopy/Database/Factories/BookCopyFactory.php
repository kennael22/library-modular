<?php

namespace Modules\BookCopy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\BookCopy\Models\BookCopy;

class BookCopyFactory extends Factory
{
    protected $model = BookCopy::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(4);

        return [
            'name' => $name,            
            
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
