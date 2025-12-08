<?php

namespace Database\Factories;

use App\Models\Entregable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entregable>
 */
class EntregableFactory extends Factory
{
    protected $model = Entregable::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaLimite = $this->faker->dateTimeBetween('+1 week', '+2 months');

        return [
            'competencia_id' => null, // Will be set by seeder
            'nombre' => 'Entregable: ' . $this->faker->word(),
            'fecha_limite' => $fechaLimite,
            'formatos_aceptados' => $this->faker->randomElement(['pdf,doc,docx', 'pdf', 'jpg,png,gif', 'zip,rar,7z']),
        ];
    }
}
