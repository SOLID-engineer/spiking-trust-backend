<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->domainWord();
        return [
            'domain' => $this->faker->unique()->regexify($name . '\.(com|vn|com\.vn|fn|fr|jp|vip)'),
            'name' => $name,
            'description' => $this->faker->text(200),
        ];
    }
}
