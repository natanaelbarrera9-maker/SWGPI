<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Policy lo maneja
    }

    public function rules()
    {
        return [
            'calificacion' => ['required', 'integer', 'min:0', 'max:100'],
            'comentarios' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'calificacion.required' => 'La calificación es requerida',
            'calificacion.integer' => 'La calificación debe ser un número entero',
            'calificacion.min' => 'La calificación mínima es 0',
            'calificacion.max' => 'La calificación máxima es 100',
        ];
    }
}
