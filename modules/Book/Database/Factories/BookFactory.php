<?php

namespace Modules\Book\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Book\Models\Book;

class BookFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => \Modules\Author\Models\Author::factory(),
            'edition' => $this->faker->optional()->randomElement(['1st', '2nd', '3rd', '4th', '5th']),
            'volumes' => $this->faker->numberBetween(1, 3),
            'pages' => $this->faker->optional()->numberBetween(100, 800),
            'source_of_fund' => $this->faker->optional()->randomElement(['Government', 'Private', 'Donation']),
            'publisher' => $this->faker->optional()->company(),
            'publication_year' => $this->faker->optional()->year(),
            'category' => $this->faker->optional()->randomElement(['Fiction', 'Non-Fiction', 'Reference', 'Textbook']),
            'genre' => $this->faker->optional()->randomElement(['Programming', 'Fiction', 'Science', 'History', 'Biography']),
            'access_book_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{6}'),
            'quantity' => $this->faker->numberBetween(1, 10),
            'cover_image' => $this->faker->optional()->imageUrl(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
