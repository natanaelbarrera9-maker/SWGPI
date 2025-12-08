<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tabla asociada
     */
    protected $table = 'users';
    
    /**
     * Usar ID como string (no autoincrement)
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Timestamps: solo created_at (sin updated_at)
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'id',                // Matrícula/Nómina (PK)
        'nombres',
        'apa',              // Apellido paterno
        'ama',              // Apellido materno
        'email',
        'password',
        'curp',
        'direccion',
        'telefonos',
        'perfil_id',        // 1=Admin, 2=Teacher, 3=Student
        'activo',
    ];

    /**
     * Campos ocultos en JSON/arrays
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'activo' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'perfil_id' => 'integer',
    ];

    // ========================
    // RELACIONES
    // ========================

    /**
     * Proyectos creados por este usuario
     */
    public function projectsCreated(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by', 'id');
    }

    /**
     * Proyectos donde es asesor (many-to-many via project_user)
     */
    public function projectsAsAdvisor(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_user',
            'user_id',
            'project_id'
        )->withPivot('rol_asesor');
    }

    /**
     * Relación genérica proyectos del usuario (alias para uso en dashboards/vistas).
     * Utiliza el pivot `project_user` — si el usuario no está asociado como asesor,
     * la colección estará vacía. Esto permite llamadas a `$user->projects()` en vistas.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_user',
            'user_id',
            'project_id'
        )->withPivot('rol_asesor');
    }

    /**
     * Entregas de estudiantes (si es profesor)
     */
    public function entregasCalificadas(): HasMany
    {
        return $this->hasMany(EntregaEstudiante::class, 'user_id', 'id');
    }

    /**
     * Password reset tokens
     */
    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'user_id', 'id');
    }

    // ========================
    // MÉTODOS HELPER
    // ========================

    /**
     * Verificar si es administrador (perfil_id = 1)
     */
    public function isAdmin(): bool
    {
        return $this->perfil_id === 1;
    }

    /**
     * Verificar si es docente (perfil_id = 2)
     */
    public function isTeacher(): bool
    {
        return $this->perfil_id === 2;
    }

    /**
     * Verificar si es estudiante (perfil_id = 3)
     */
    public function isStudent(): bool
    {
        return $this->perfil_id === 3;
    }

    /**
     * Obtener nombre completo
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->nombres} {$this->apa} {$this->ama}");
    }

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return $this->activo === true;
    }

    /**
     * Obtener nombre del perfil
     */
    public function getPerfilName(): string
    {
        return match ($this->perfil_id) {
            1 => 'Administrador',
            2 => 'Docente',
            3 => 'Estudiante',
            default => 'Desconocido',
        };
    }

    /**
     * Scope: solo usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: solo usuarios inactivos
     */
    public function scopeInactive($query)
    {
        return $query->where('activo', false);
    }

    /**
     * Scope: solo administradores
     */
    public function scopeAdmins($query)
    {
        return $query->where('perfil_id', 1);
    }

    /**
     * Scope: solo docentes
     */
    public function scopeTeachers($query)
    {
        return $query->where('perfil_id', 2);
    }

    /**
     * Scope: solo estudiantes
     */
    public function scopeStudents($query)
    {
        return $query->where('perfil_id', 3);
    }
}
