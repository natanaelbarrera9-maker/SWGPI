<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graph extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'nombre',
        'descripcion',
        'data',
        'tipo',
    ];

    protected $casts = [
        'data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: proyecto al que pertenece
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Obtener estructura del grafo en formato visualizable
     */
    public function getVisualizationData()
    {
        return [
            'nodes' => $this->data['nodes'] ?? [],
            'links' => $this->data['links'] ?? [],
            'type' => $this->tipo,
        ];
    }
}
