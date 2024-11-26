<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     *
     * @return bool
     */
    public function authorize()
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $this->route('client'), // Exceção para UPDATE
            'address' => 'required|string',
        ];
    }

    /**
     * Retorna as mensagens de erro personalizadas.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string válida.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.string' => 'O e-mail deve ser uma string válida.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.unique' => 'Já existe um cliente com este e-mail.',
            'address.required' => 'O endereço é obrigatório.',
            'address.string' => 'O endereço deve ser uma string válida.',
        ];
    }
}
