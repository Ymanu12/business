<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->paragraph,
            'content' => $this->faker->paragraphs(3, true),
            'category' => 'Music festival',
            'author' => $this->faker->name,
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'views' => $this->faker->numberBetween(0, 1000),
            'image' => 'img/blog/blog-'.$this->faker->numberBetween(1,6).'.jpg',
            'thumbnail' => 'img/blog/br-'.$this->faker->numberBetween(1,4).'.jpg',
        ];
    }
}
