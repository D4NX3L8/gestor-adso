<?php

namespace App\Http\Requests;

use App\Models\Aprendiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateAprendizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $aprendiz = $this->route('aprendiz');
        $id = $aprendiz instanceof Aprendiz ? $aprendiz->id : null;

        return [
            'nombre' => ['required', 'string', 'max:120'],
            'documento' => [
                'required',
                'string',
                'max:40',
                Rule::unique('aprendices', 'documento')->ignore($id),
            ],
            'correo' => [
                'required',
                'email',
                'max:120',
                Rule::unique('aprendices', 'correo')->ignore($id),
            ],
            'ficha_id' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar 120 caracteres.',
            'documento.required' => 'El documento es obligatorio.',
            'documento.string' => 'El documento debe ser texto.',
            'documento.max' => 'El documento no puede superar 40 caracteres.',
            'documento.unique' => 'El documento ya existe.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo no es válido.',
            'correo.max' => 'El correo no puede superar 120 caracteres.',
            'correo.unique' => 'El correo ya existe.',
            'ficha_id.integer' => 'La ficha debe ser numérica.',
            'ficha_id.min' => 'La ficha no puede ser negativa.',
        ];
    }
}
