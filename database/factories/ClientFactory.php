<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'portal_id' => \App\Models\Portal::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'company' => $this->faker->optional()->company(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'address' => $this->faker->optional()->address(),
            'avatar_path' => null,
            'email_verified_at' => $this->faker->optional()->dateTime(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'preferences' => null,
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'last_login_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
