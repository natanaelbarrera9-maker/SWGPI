<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    protected $fillable = [
        'asignatura_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
    ];


    /**
     * Relación: proyecto al que pertenece
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
