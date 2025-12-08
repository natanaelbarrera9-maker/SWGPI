<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Deliverable;

class DeliverablePolicy
{
    /**
     * Ver entregables
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher() || $user->isStudent();
    }

    /**
     * Ver un entregable específico
     */
    public function view(User $user, Deliverable $deliverable): bool
    {
        // Admin ve todo
        if ($user->isAdmin()) {
            return true;
        }

        // Docente ve si asesora el proyecto
        if ($user->isTeacher() && $deliverable->project->advisor_id === $user->id) {
            return true;
        }

        // Estudiante ve si lo envió
        if ($user->isStudent() && $deliverable->submitted_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Estudiante puede enviar/actualizar su entregable
     */
    public function submit(User $user, Deliverable $deliverable): bool
    {
        // Solo estudiante puede enviar
        if (!$user->isStudent()) {
            return false;
        }

        // Solo puede enviar si el proyecto existe
        if (!$deliverable->project || !$deliverable->project->activo) {
            return false;
        }

        // Participar en el proyecto
        if (!$user->projects->contains($deliverable->project)) {
            return false;
        }

        return true;
    }

    /**
     * Descargar un entregable
     */
    public function download(User $user, Deliverable $deliverable): bool
    {
        // Admin descarga todo
        if ($user->isAdmin()) {
            return true;
        }

        // Docente si asesora
        if ($user->isTeacher() && $deliverable->project->advisor_id === $user->id) {
            return true;
        }

        // Estudiante si lo envió
        if ($user->isStudent() && $deliverable->submitted_by === $user->id) {
            return true;
        }

        return false;
    }
}
