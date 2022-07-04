<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\User;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
			return [
				'user_id' => User::factory(),
				'title' =>  $this->faker->sentence,
				'content' =>  $this->faker->paragraphs,
			];
		}

}
