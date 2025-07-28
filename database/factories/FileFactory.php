<?php

namespace Database\Factories;

use App\Models\Portal;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = $this->faker->word() . '.' . $this->faker->randomElement(['jpg', 'png', 'pdf', 'docx', 'txt']);
        
        return [
            'portal_id' => Portal::factory(),
            'client_id' => null,
            'project_id' => null,
            'uploaded_by' => User::factory(),
            'name' => $fileName,
            'original_name' => $fileName,
            'file_path' => 'portals/' . $this->faker->uuid() . '/files/' . $fileName,
            'mime_type' => $this->faker->randomElement([
                'image/jpeg',
                'image/png', 
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain'
            ]),
            'file_size' => $this->faker->numberBetween(1024, 5242880), // 1KB to 5MB
            'file_hash' => $this->faker->optional()->sha256(),
            'description' => $this->faker->optional()->sentence(),
            'metadata' => $this->faker->optional()->randomElement([null, ['version' => 1, 'tags' => ['important']]]),
            'is_public' => $this->faker->boolean(30), // 30% chance of being public
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'download_count' => $this->faker->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the file is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Indicate that the file is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Indicate that the file is an image.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->word() . '.jpg',
            'original_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    /**
     * Indicate that the file is a PDF.
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->word() . '.pdf',
            'original_name' => $this->faker->word() . '.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    /**
     * Indicate that the file belongs to a project.
     */
    public function withProject(): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => Project::factory(),
        ]);
    }

    /**
     * Indicate that the file belongs to a client.
     */
    public function withClient(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => Client::factory(),
        ]);
    }
}
