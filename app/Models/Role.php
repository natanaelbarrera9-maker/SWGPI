<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $table = 'roles';
    public $timestamps = false;

    /**
     * Relación: un rol tiene muchos usuarios
     */
    public function users()
    {
        // Mantener compatibilidad con código antiguo: mapear a `perfil_id` en users
        return $this->hasMany(User::class, 'perfil_id');
    }
}
