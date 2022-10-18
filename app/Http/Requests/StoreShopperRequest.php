<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShopperRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document' => [
                'sometimes',
                'required',
                Rule::unique('shoppers', 'document')->ignore(request()->route('id'), 'id')
            ],
            'full_name' => 'sometimes|required|max:150',
            'cellphone' => 'sometimes|required|digits:10',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('shoppers', 'email')->ignore(request()->route('id'), 'id')
            ],
            'status' => 'sometimes|boolean'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'document.required' => "Documento es requerido.",
            'document.unique' => "El documento ya está en uso.",
            'full_name.required' => "Nombre completo es requerido.",
            'full_name.max' => "El nombre no debe tener más de 150 caracteres.",
            'cellphone.required' => "Celular es requerido.",
            'cellphone.digits' => "El celular debe ser de 10 dígitos.",
            'email.required' => "Correo electrónico es requerido.",
            'email.unique' => "El correo electrónico ya está en uso.",
            'email.email' => "El correo debe ser una dirección de correo electrónico válida.",
            'status.boolean' => "Estado invalido."
        ];
    }
}
