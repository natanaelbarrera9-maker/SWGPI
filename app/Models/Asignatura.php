<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asignatura extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     */
    public $timestamps = false;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'asignaturas';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'clave',
        'nombre',
    ];

    /**
     * Obtener todas las competencias para esta asignatura
     */
    public function competencias(): HasMany
    {
        return $this->hasMany(Competencia::class, 'asignatura_id');
    }

    /**
     * Obtener todos los proyectos para esta asignatura (many-to-many)
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_asignatura',
            'asignatura_id',
            'project_id'
        );
    }
}
