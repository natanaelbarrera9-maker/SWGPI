<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'projects';

    /**
     * Timestamps personalizados (solo created_at)
     */
    public $timestamps = true;
    const UPDATED_AT = null;

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'title',
        'description',
        'created_by',
        'activo',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
    ];

    // ========================
    // RELACIONES
    // ========================

    /**
     * Obtener el usuario que creó este proyecto
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Obtener todos los asesores/advisors de este proyecto (many-to-many)
     */
    public function advisors(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'project_user',
            'project_id',
            'user_id'
        )->withPivot('rol_asesor');
    }

    /**
     * Obtener asesores primarios
     */
    public function primaryAdvisors()
    {
        return $this->advisors()
            ->where('project_user.rol_asesor', 'primario')
            ->get();
    }

    /**
     * Obtener asesores secundarios
     */
    public function secondaryAdvisors()
    {
        return $this->advisors()
            ->where('project_user.rol_asesor', 'secundario')
            ->get();
    }

    /**
     * Obtener todas las asignaturas asociadas (many-to-many)
     */
    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'project_asignatura',
            'project_id',
            'asignatura_id'
        );
    }

    /**
     * Obtener todos los avances del proyecto
     */
    public function avances(): HasMany
    {
        return $this->hasMany(Avance::class, 'project_id');
    }

    /**
     * Obtener todo el feedback del proyecto
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'project_id');
    }

    /**
     * Obtener todos los entregables antiguos
     */
    public function entregablesAntiguos(): HasMany
    {
        return $this->hasMany(EntregableAntiguo::class, 'project_id');
    }

    /**
     * Obtener todas las entregas de estudiantes (a través de entregables → competencias → asignaturas)
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaEstudiante::class, 'project_id');
    }

    // ========================
    // MÉTODOS HELPER
    // ========================

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return $this->activo === true;
    }

    /**
     * Obtener el progreso general (% de entregas calificadas)
     */
    public function getProgress(): float
    {
        $totalEntregas = $this->entregas()->count();
        if ($totalEntregas === 0) {
            return 0;
        }

        $entregasCalificadas = $this->entregas()
            ->whereNotNull('calificacion')
            ->count();

        return ($entregasCalificadas / $totalEntregas) * 100;
    }

    /**
     * Obtener promedio de calificaciones
     */
    public function getAverageGrade(): float|null
    {
        return $this->entregas()
            ->whereNotNull('calificacion')
            ->avg('calificacion');
    }

    /**
     * Scope para proyectos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para proyectos inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    /**
     * Scope para búsqueda
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%");
    }
}
