<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grafo extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     */
    public $timestamps = false;

    /**
     * Nombre de la columna created_at personalizada
     */
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'grafos';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'nombre_archivo',
        'status',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'status' => 'string', // enum('activo', 'inactivo')
        'fecha_creacion' => 'datetime',
    ];

    /**
     * Scope para obtener grafos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('status', 'activo');
    }

    /**
     * Scope para obtener grafos inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('status', 'inactivo');
    }

    /**
     * Alternar estado del grafo
     */
    public function toggleStatus(): void
    {
        $this->status = $this->status === 'activo' ? 'inactivo' : 'activo';
        $this->save();
    }
}
