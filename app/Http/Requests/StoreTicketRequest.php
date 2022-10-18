<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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
            'code' => [
                'required',
                Rule::unique('tickets', 'code')->ignore(request()->route('id'), 'id')
            ],
            'description' => 'required',
            'cost' => 'required'
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
            'code.required' => "Código es requerido.",
            'code.unique' => "El código ya ha sido asignado.",
            'description.required' => "Descripción es requerido.",
            'cost.required' => "Precio es requerido.",
        ];
    }
}
