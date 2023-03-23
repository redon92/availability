<?php

namespace Tests\Feature;

use App\Models\UserAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAvailabilityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStore()
    {
        $data = [
            'user_id' => $this->faker->randomNumber(),
            'date' => $this->faker->date(),
            'is_available' => $this->faker->boolean(),
        ];

        $response = $this->postJson(route('availability.store'), $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'User availability stored successfully',
                'user_availability' => $data,
            ]);

        $this->assertDatabaseHas('user_availability', $data);
    }

    public function testUpdateAll()
    {
        $data = [
            'user_id' => $this->faker->randomNumber(),
            'is_available' => $this->faker->boolean(),
        ];

        $response = $this->postJson(route('availability.updateall'), $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'User availability updated for the current month',
            ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth()->addMonth();

        for ($date = $startDate; $date <= $endDate; $date = $date->addDay()) {
            $this->assertDatabaseHas('user_availability', [
                'user_id' => $data['user_id'],
                'date' => $date->toDateString(),
                'is_available' => $data['is_available'],
            ]);
        }
    }

    public function testGetAvailability()
    {
        $user_id = $this->faker->randomNumber();
        $date = $this->faker->date();
        $availabilities = [];

        for ($i = -2; $i <= 2; $i++) {
            $targetDate = (new \DateTime($date))->modify("$i months");
            $availability = UserAvailability::factory()->create([
                'user_id' => $user_id,
                'date' => $targetDate->format('Y-m-d'),
                'is_available' => $this->faker->boolean(),
            ]);
            $availabilities[$targetDate->format('Y-m-d')] = $availability->is_available;
        }

        $response = $this->getJson(route('availability.get', [
            'user_id' => $user_id,
            'date' => $date,
        ]));

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'User availability fetched successfully',
                'availability' => $availabilities,
            ]);
    }
}
