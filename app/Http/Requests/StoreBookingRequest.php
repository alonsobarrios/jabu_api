<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
        $rules['status'] = 'sometimes|boolean';

        if (!request()->route('id')) {
            $rules['ticket_id'] = [
                'required', 
                'numeric', 
                'exists:tickets,id',
                function ($attribute, $value, $fail) {
                    $exists = Booking::where($attribute, $value)->where('status', 1)->first();
                    if ($exists) {
                        $fail('Boleta no disponible.');
                    }
                }
            ];
            $rules['shopper_id'] = ['required', 'numeric', 'exists:shoppers,id'];
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ticket_id.required' => "Boleta es requerido.",
            'ticket_id.numeric' => "Boleta debe ser numérico.",
            'ticket_id.exists' => "Boleta no válida.",
            'shopper_id.required' => "Comprador es requerido.",
            'shopper_id.numeric' => "Comprador debe ser numérico.",
            'shopper_id.exists' => "Comprador no válido.",
            'status.boolean' => "Estado invalido."
        ];
    }
}
