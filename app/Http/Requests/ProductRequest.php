<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {

        throw new HttpResponseException(response()->json([
            'erros' => $validator->errors()
        ],422));
    }

    /**
     * Obtenha as regras de validação que se aplicam à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'brand' => 'required',
            'quantity' => 'required',
            'price'=> 'required',
        ];
    }

    /**
     * Retorna as mensagens de erro personalizadas.
     *
     * @return array
     */
    public function messages(): array {

        return[
            'name.required' => 'Campo nome é obrigatório!',
            'brand.required' => 'Campo marca é obrigatório!',
            'quantity.required' => 'Campo quantidade é obrigatório!',
            'price.required' => 'Campo preço é obrigatório!',
        ];
    }
}
