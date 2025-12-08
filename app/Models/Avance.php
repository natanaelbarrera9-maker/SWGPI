<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avance extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     */
    public $timestamps = true;

    /**
     * Actualizar solo created_at
     */
    const UPDATED_AT = null;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'avances';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'project_id',
        'note',
        'milestone_date',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'milestone_date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Obtener el proyecto al que pertenece este avance
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Scope para obtener avances completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('milestone_date', '<', now());
    }

    /**
     * Scope para obtener avances pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('milestone_date', '>=', now());
    }
}
