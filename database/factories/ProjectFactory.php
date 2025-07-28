<?php

namespace Database\Factories;

use App\Models\Portal;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        
        return [
            'portal_id' => Portal::factory(),
            'client_id' => Client::factory(),
            'name' => $name,
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'active', 'completed', 'on_hold', 'cancelled']),
            'start_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'deadline' => $this->faker->dateTimeBetween('now', '+6 months'),
            'budget' => $this->faker->randomFloat(2, 1000, 50000),
            'progress' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the project is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the project is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'progress' => 100,
        ]);
    }

    /**
     * Indicate that the project is in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'progress' => 0,
        ]);
    }
}
