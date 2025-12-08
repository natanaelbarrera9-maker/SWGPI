<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'deliverable_id',
        'graded_by',
        'calificacion',
        'comentarios',
        'graded_at',
    ];

    protected $casts = [
        'calificacion' => 'integer',
        'graded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELACIONES
    // ========================

    /**
     * Relación: entregable que fue calificado
     */
    public function deliverable()
    {
        return $this->belongsTo(Deliverable::class);
    }

    /**
     * Relación: usuario que hizo la calificación
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by', 'id');
    }

    // ========================
    // MÉTODOS HELPER
    // ========================

    /**
     * Obtener calificación en letra
     */
    public function getGradeLetterAttribute(): string
    {
        return match(true) {
            $this->calificacion >= 90 => 'A',
            $this->calificacion >= 80 => 'B',
            $this->calificacion >= 70 => 'C',
            $this->calificacion >= 60 => 'D',
            default => 'F',
        };
    }

    /**
     * Verificar si es aprobado
     */
    public function isPassed(): bool
    {
        return $this->calificacion >= 70;
    }
}
