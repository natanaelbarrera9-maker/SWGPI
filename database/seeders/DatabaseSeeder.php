<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Asignatura;
use App\Models\Competencia;
use App\Models\Entregable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios iniciales
        $this->seedUsers();
        
        // Crear asignaturas
        $this->seedAsignaturas();
        
        // Crear proyectos
        $this->seedProjects();
    }

    /**
     * Seed users: 1 admin, 2 teachers, 5 students
     */
    private function seedUsers(): void
    {
        // Admin
        User::factory()
            ->admin()
            ->create([
                'id' => '0000000001',
                'nombres' => 'Admin',
                'apa' => 'System',
                'ama' => 'User',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
            ]);

        // Teachers
        User::factory()
            ->teacher()
            ->create([
                'id' => '0000000002',
                'nombres' => 'Docente',
                'apa' => 'Uno',
                'ama' => 'Test',
                'email' => 'teacher1@example.com',
                'password' => bcrypt('teacher123'),
            ]);

        User::factory()
            ->teacher()
            ->create([
                'id' => '0000000003',
                'nombres' => 'Docente',
                'apa' => 'Dos',
                'ama' => 'Test',
                'email' => 'teacher2@example.com',
                'password' => bcrypt('teacher123'),
            ]);

        // Students
        for ($i = 4; $i <= 8; $i++) {
            User::factory()
                ->student()
                ->create([
                    'id' => str_pad((string)$i, 10, '0', STR_PAD_LEFT),
                    'email' => "student{$i}@example.com",
                    'password' => bcrypt('student123'),
                ]);
        }
    }

    /**
     * Seed asignaturas, competencias, entregables
     */
    private function seedAsignaturas(): void
    {
        $asignaturasData = [
            [
                'clave' => 'PROG-101',
                'nombre' => 'Programación Básica',
                'competencias' => 3,
            ],
            [
                'clave' => 'BD-101',
                'nombre' => 'Bases de Datos',
                'competencias' => 2,
            ],
            [
                'clave' => 'WEB-101',
                'nombre' => 'Desarrollo Web',
                'competencias' => 3,
            ],
        ];

        foreach ($asignaturasData as $asigData) {
            $asignatura = Asignatura::factory()->create([
                'clave' => $asigData['clave'],
                'nombre' => $asigData['nombre'],
            ]);

            // Crear competencias para cada asignatura
            for ($i = 0; $i < $asigData['competencias']; $i++) {
                $competencia = Competencia::factory()->create([
                    'asignatura_id' => $asignatura->id,
                    'nombre' => "Competencia " . ($i + 1) . " de " . $asigData['nombre'],
                ]);

                // Crear 2 entregables por competencia
                for ($j = 0; $j < 2; $j++) {
                    Entregable::factory()->create([
                        'competencia_id' => $competencia->id,
                        'nombre' => "Entregable " . ($j + 1),
                    ]);
                }
            }
        }
    }

    /**
     * Seed projects con relaciones
     */
    private function seedProjects(): void
    {
        $admin = User::where('perfil_id', 1)->first();
        $teachers = User::where('perfil_id', 2)->get();
        $asignaturas = Asignatura::all();

        // Crear 3 proyectos
        for ($i = 1; $i <= 3; $i++) {
            $project = Project::factory()->create([
                'title' => "Proyecto Académico #{$i}",
                'created_by' => $admin?->id,
            ]);

            // Asociar asignaturas (random)
            $project->asignaturas()
                ->attach($asignaturas->random(2)->pluck('id')->toArray());

            // Asociar asesores (teachers)
            foreach ($teachers->take(2) as $index => $teacher) {
                $project->advisors()->attach($teacher->id, [
                    'rol_asesor' => $index === 0 ? 'primario' : 'secundario',
                ]);
            }
        }
    }
}
