<?php

namespace Database\Factories;

use App\Models\Asignatura;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asignatura>
 */
class AsignaturaFactory extends Factory
{
    protected $model = Asignatura::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clave' => strtoupper($this->faker->bothify('???-###')),
            'nombre' => $this->faker->word() . ' ' . $this->faker->word(),
        ];
    }
}
