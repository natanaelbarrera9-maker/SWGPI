<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competencia extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     */
    public $timestamps = false;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'competencias';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'asignatura_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Obtener la asignatura a la que pertenece esta competencia
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    /**
     * Obtener todos los entregables para esta competencia
     */
    public function entregables(): HasMany
    {
        return $this->hasMany(Entregable::class, 'competencia_id');
    }
}
