<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grade;

class GradePolicy
{
    /**
     * Docente solo ve calificaciones de sus proyectos
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    /**
     * Ver una calificación específica
     */
    public function view(User $user, Grade $grade): bool
    {
        // Admin ve todo
        if ($user->isAdmin()) {
            return true;
        }

        // Docente si es el que calificó o asesora el proyecto
        if ($user->isTeacher()) {
            return $grade->graded_by === $user->id || 
                   $grade->deliverable->project->advisor_id === $user->id;
        }

        return false;
    }

    /**
     * Solo docente puede crear calificaciones (en sus proyectos)
     */
    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    /**
     * Solo docente puede actualizar
     */
    public function update(User $user, Grade $grade): bool
    {
        // Solo el que calificó o admin
        return $user->isAdmin() || $grade->graded_by === $user->id;
    }
}
