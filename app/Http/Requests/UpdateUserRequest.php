<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Policy lo maneja
    }

    public function rules()
    {
        $userId = $this->route('user')->id;

        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apa' => ['required', 'string', 'max:50'],
            'ama' => ['nullable', 'string', 'max:50'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id')
            ],
            'password' => ['nullable', 'min:6', 'confirmed'],  // Opcional
            'perfil_id' => ['required', 'in:1,2,3'],
            'curp' => ['nullable', 'string', 'max:18'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefonos' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            'nombres.required' => 'El nombre es requerido',
            'apa.required' => 'El apellido paterno es requerido',
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Este email ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'perfil_id.required' => 'Debes seleccionar un perfil',
            'perfil_id.in' => 'El perfil seleccionado no es válido',
        ];
    }
}
