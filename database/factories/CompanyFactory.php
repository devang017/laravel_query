<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'industry' => fake()->randomElement([
                'Retail',
                'Software',
                'Manufacturing',
                'Healthcare',
                'Finance',
                'Education',
                'Logistics',
                'Hospitality',
                'Media',
                'Real Estate',
            ]),
            'created_at' => fake()->dateTimeBetween('-3 years')->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
