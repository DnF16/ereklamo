<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['maintenance', 'noise', 'safety', 'cleanliness', 'other']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'resolved', 'closed']),
        ];
    }
}
