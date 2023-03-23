<?php

namespace Database\Factories;

use App\Models\UserAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAvailability>
 */
class UserAvailabilityFactory extends Factory
{
    protected $model = UserAvailability::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'date' => $this->faker->date(),
            'is_available' => $this->faker->boolean(),
        ];
    }
}
