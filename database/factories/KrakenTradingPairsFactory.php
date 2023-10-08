<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\KrakenTradingPairs;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class KrakenTradingPairsFactory extends Factory
{
    protected $model = KrakenTradingPairs::class;

    public function definition(): array
    {
        return [
            'key_pair' => $this->faker->word(),
            'crypto' => $this->faker->word(),
            'fiat' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
