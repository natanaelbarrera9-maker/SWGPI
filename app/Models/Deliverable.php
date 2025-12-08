<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'nombre',
        'descripcion',
        'archivo_path',
        'fecha_entrega',
        'estado',
        'submitted_by',
        'submitted_at',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELACIONES
    // ========================

    /**
     * Relación: proyecto al que pertenece
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relación: usuario que lo envió
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    /**
     * Relación: calificación asociada
     */
    public function grade()
    {
        return $this->hasOne(Grade::class);
    }

    // ========================
    // MÉTODOS HELPER
    // ========================

    /**
     * Verificar si el entregable está atrasado
     */
    public function isLate(): bool
    {
        if (!$this->submitted_at) {
            return $this->fecha_entrega < now();
        }
        return $this->submitted_at > $this->fecha_entrega;
    }

    /**
     * Obtener color del estado para UI
     */
    public function getStatusColorClass(): string
    {
        return match($this->estado) {
            'entregado' => 'info',
            'revisado' => 'success',
            'devuelto' => 'warning',
            'pendiente' => 'secondary',
            default => 'light',
        };
    }

    /**
     * Scope: solo entregables pendientes de revisión
     */
    public function scopePendingReview($query)
    {
        return $query->whereIn('estado', ['entregado', 'devuelto']);
    }

    /**
     * Scope: solo entregables ya revisados
     */
    public function scopeReviewed($query)
    {
        return $query->where('estado', 'revisado');
    }
}
