<?php

namespace Database\Factories;

use App\Models\Competencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competencia>
 */
class CompetenciaFactory extends Factory
{
    protected $model = Competencia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = $this->faker->dateTimeBetween('-6 months');
        $fechaFin = $this->faker->dateTimeBetween($fechaInicio, '+6 months');

        return [
            'asignatura_id' => null, // Will be set by seeder
            'nombre' => 'Competencia: ' . $this->faker->word(),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
    }
}
