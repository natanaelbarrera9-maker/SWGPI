<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $idCounter = 1;
        
        return [
            'id' => str_pad($idCounter++, 10, '0', STR_PAD_LEFT), // 0000000001, 0000000002...
            'nombres' => $this->faker->firstName(),
            'apa' => $this->faker->lastName(),
            'ama' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'curp' => strtoupper($this->faker->bothify('??????????????')),
            'direccion' => $this->faker->address(),
            'telefonos' => $this->faker->phoneNumber(),
            'perfil_id' => 3, // Default: Student
            'activo' => true,
            'created_at' => now(),
        ];
    }

    /**
     * Indicar que es administrador
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'perfil_id' => 1,
        ]);
    }

    /**
     * Indicar que es docente
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'perfil_id' => 2,
        ]);
    }

    /**
     * Indicar que es estudiante
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'perfil_id' => 3,
        ]);
    }

    /**
     * Indicar que está inactivo
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }
}
