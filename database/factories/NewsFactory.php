<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;


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
				'title' => $faker->sentence,
				'content' => $faker->paragraphs,
				'user_id' => factory('App\User')->create()->id,
			];
		}

}
