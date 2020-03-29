<?php

namespace App\Domains\User\Auth\Validators;

use App\Domains\AbstractInputValidator;

final class RegisterValidator extends AbstractInputValidator
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::rules()
     */
    protected function rules(): array
    {
        return [
            'firstname' => 'required|string|min:3|max:15',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8'
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::messages()
     */
    protected function messages(): array
    {
        return [
            'firstname.required' => 'Firstname jest wymagane',
            'firstname.min' => 'Firstname jest zbyt krótkie (min. 3 znaki)',
            'firstname.max' => 'Firstname jest zbyt długie (max. 15 znaków)',
            'email.required' => 'Email jest wymagany',
            'email.email' => 'To nie jest prawidłowy adres email',
            'email.max' => 'Email jest zbyt długi (max. 255 znaków)',
            'email.unique' => 'Taki email już istnieje',
            'passord.required' => 'Hasło jest wymagane',
            'password.min' => 'Hasło jest za krótkie (min. 8 znaków)'
        ];
    }
}
