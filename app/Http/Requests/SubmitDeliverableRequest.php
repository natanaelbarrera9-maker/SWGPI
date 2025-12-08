<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDeliverableRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Policy lo maneja
    }

    public function rules()
    {
        return [
            'archivo' => [
                'required',
                'file',
                'mimes:pdf,zip,rar,doc,docx,txt,jpg,png',
                'max:10240',  // 10 MB
            ],
        ];
    }

    public function messages()
    {
        return [
            'archivo.required' => 'Debes seleccionar un archivo',
            'archivo.file' => 'Debes subir un archivo válido',
            'archivo.mimes' => 'Solo se permiten: PDF, ZIP, RAR, DOC, DOCX, TXT, JPG, PNG',
            'archivo.max' => 'El archivo no debe superar 10 MB',
        ];
    }
}
