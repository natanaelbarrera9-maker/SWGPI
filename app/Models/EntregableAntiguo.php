<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntregableAntiguo extends Model
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
    protected $table = 'entregables_antiguos';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'project_id',
        'filename',
        'notes',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Obtener el proyecto al que pertenece este entregable antiguo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
