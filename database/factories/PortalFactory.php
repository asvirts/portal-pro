<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portal>
 */
class PortalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'subdomain' => $this->faker->optional()->slug(),
            'custom_domain' => $this->faker->optional()->domainName(),
            'description' => $this->faker->optional()->paragraph(),
            'logo_path' => null,
            'primary_color' => $this->faker->hexColor(),
            'secondary_color' => $this->faker->hexColor(),
            'branding_settings' => null,
            'portal_settings' => null,
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
