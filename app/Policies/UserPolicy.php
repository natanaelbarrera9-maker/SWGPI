<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Solo administradores pueden ver la lista de usuarios
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Ver detalles de un usuario
     */
    public function view(User $user, User $model): bool
    {
        // Admin ve a todos
        if ($user->isAdmin()) {
            return true;
        }

        // Usuario ve su propio perfil
        return $user->id === $model->id;
    }

    /**
     * Solo admin puede crear usuarios
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede actualizar usuarios
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede eliminar/desactivar
     */
    public function delete(User $user, User $model): bool
    {
        // No permitir que un admin se elimine a sí mismo
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }
}
