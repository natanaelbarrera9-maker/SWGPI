<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // El middleware ya verificó role:admin
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
                'string',
                'max:20',
                'unique:users,id',  // Verificar ID único
                'regex:/^[A-Z0-9]+$/',  // Solo mayúsculas y números
            ],
            'nombres' => ['required', 'string', 'max:100'],
            'apa' => ['required', 'string', 'max:50'],
            'ama' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'perfil_id' => ['required', 'in:1,2,3'],
            'curp' => ['nullable', 'string', 'max:18'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefonos' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El ID (matrícula/nómina) es requerido',
            'id.unique' => 'Este ID ya está registrado',
            'id.regex' => 'El ID solo puede contener letras mayúsculas y números',
            'nombres.required' => 'El nombre es requerido',
            'apa.required' => 'El apellido paterno es requerido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'perfil_id.required' => 'Debes seleccionar un perfil',
            'perfil_id.in' => 'El perfil seleccionado no es válido',
        ];
    }
}
