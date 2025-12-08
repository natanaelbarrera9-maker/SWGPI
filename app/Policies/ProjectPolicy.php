<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * Solo admin puede ver todos los proyectos
     * Docentes ven sus proyectos
     * Estudiantes ven sus proyectos
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher() || $user->isStudent();
    }

    /**
     * Ver un proyecto específico
     */
    public function view(User $user, Project $project): bool
    {
        // Admin ve todo
        if ($user->isAdmin()) {
            return true;
        }

        // Docente ve si es asesor
        if ($user->isTeacher() && $project->advisor_id === $user->id) {
            return true;
        }

        // Estudiante ve si participa
        if ($user->isStudent()) {
            return $user->projects->contains($project);
        }

        return false;
    }

    /**
     * Solo admin puede crear proyectos
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede actualizar
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede eliminar
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }
}
