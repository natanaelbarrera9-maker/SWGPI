<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entregable extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     */
    public $timestamps = false;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'entregables';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'competencia_id',
        'nombre',
        'fecha_limite',
        'formatos_aceptados',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'fecha_limite' => 'datetime',
    ];

    /**
     * Obtener la competencia a la que pertenece este entregable
     */
    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class, 'competencia_id');
    }

    /**
     * Obtener todas las entregas de estudiantes para este entregable
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaEstudiante::class, 'entregable_id');
    }

    /**
     * Scope para obtener entregables vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('fecha_limite', '<', now());
    }

    /**
     * Scope para obtener entregables próximos
     */
    public function scopeProximos($query)
    {
        return $query->where('fecha_limite', '>', now())
            ->where('fecha_limite', '<', now()->addDays(7));
    }
}
