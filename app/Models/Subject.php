<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'clave',
        'nombre',
    ];


    /**
     * Relación: proyectos asociados
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_subjects', 'subject_id', 'project_id');
    }
}
