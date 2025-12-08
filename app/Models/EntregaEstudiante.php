<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntregaEstudiante extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     * Solo tiene created_at como fecha_entrega
     */
    public $timestamps = false;

    /**
     * Nombre de la columna created_at personalizada
     */
    const CREATED_AT = 'fecha_entrega';
    const UPDATED_AT = null;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'entregas_estudiantes';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'entregable_id',
        'user_id',
        'project_id',
        'nombre_archivo',
        'ruta_archivo',
        'fecha_entrega',
        'calificacion',
        'comentarios_docente',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'fecha_entrega' => 'datetime',
        'calificacion' => 'decimal:2',
    ];

    /**
     * Obtener el entregable al que pertenece esta entrega
     */
    public function entregable(): BelongsTo
    {
        return $this->belongsTo(Entregable::class, 'entregable_id');
    }

    /**
     * Obtener el estudiante que realizó la entrega
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Obtener el proyecto al que pertenece esta entrega
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Verificar si la entrega está calificada
     */
    public function isCalificada(): bool
    {
        return $this->calificacion !== null;
    }

    /**
     * Verificar si la entrega fue entregada a tiempo
     */
    public function esAtiempo(): bool
    {
        return $this->fecha_entrega <= $this->entregable->fecha_limite;
    }
}
