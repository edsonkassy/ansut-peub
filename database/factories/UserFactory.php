<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => fake()->randomElement(['bachelier', 'partenaire', 'admin']),
            'status' => fake()->randomElement(['active', 'pending']),
            'last_login_at' => fake()->optional()->dateTime(),
            'otp_code' => fake()->optional()->numerify('######'),
            'otp_expires_at' => fake()->optional()->dateTime(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a bachelier.
     */
    public function bachelier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'bachelier',
        ]);
    }

    /**
     * Indicate that the user is a partenaire.
     */
    public function partenaire(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'partenaire',
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
