<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FioTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FioTransaction>
 */
class FioTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fioId' => Str::random(),
            'transactionTimestamp' => now(),
            'amount' => random_int(-10000, 10000),
            'note' => $this->faker->sentence(),
            'reference' => $this->faker->sentence(),
            'comment' => $this->faker->sentence(),
        ];
    }
}
