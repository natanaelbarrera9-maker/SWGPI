<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        // Middleware ya verificó admin
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'advisor_id' => ['nullable', 'exists:users,id'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del proyecto es requerido',
            'fecha_inicio.required' => 'La fecha de inicio es requerida',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio',
            'subjects.*.exists' => 'Una de las asignaturas seleccionadas no existe',
        ];
    }
}
