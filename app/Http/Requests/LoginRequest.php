<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => ['required', 'string', 'exists:users,id'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El ID es requerido',
            'id.exists' => 'El usuario no existe',
            'password.required' => 'La contraseña es requerida',
        ];
    }
}
