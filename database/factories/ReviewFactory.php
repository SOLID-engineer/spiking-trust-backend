<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rating' => $this->faker->numberBetween(1,5),
            'company_id' => Company::all()->random(1)->first()->id,
            'author_id' => User::all()->random(1)->first()->id,
            'title' => $this->faker->text(100),
            'content' => $this->faker->paragraph(),
            'source' => Review::SOURCE_ORGANIC,
            'ip_address' => mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255),
        ];
    }
}
