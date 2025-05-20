<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/',
            'lastname' => 'required|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/',
            'id_number' => 'required|digits_between:6,15|unique:clients,id_number',
            'email' => 'required|email|max:100|unique:clients,email',
            'phone' => 'required|digits:10|regex:/^3\d{9}$/',
            'department' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'habeas_data' => 'required|accepted'
        ];
    }
}
