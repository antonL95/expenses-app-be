<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\KrakenKeyPairCurrentPrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class KrakenKeyPairCurrentPriceFactory extends Factory
{
    protected $model = KrakenKeyPairCurrentPrice::class;

    public function definition(): array
    {
        return [
            'keyPairId' => $this->faker->randomNumber(),
            'lastTradeClosed' => $this->faker->randomFloat(),
            'ask' => $this->faker->randomFloat(),
            'bid' => $this->faker->randomFloat(),
            'low' => $this->faker->randomFloat(),
            'high' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
