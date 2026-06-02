<?php

namespace Database\Factories;

use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KnowledgeBase>
 */
class KnowledgeBaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'original_filename' => $this->faker->word() . '.pdf',
            'file_path' => 'manuals/' . $this->faker->uuid() . '.pdf',
            'status' => 'pending',
            'error_message' => null,
        ];
    }
}
