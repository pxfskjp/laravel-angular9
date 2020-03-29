<?php

namespace App\Domains\User\Auth\Validators;

use App\Domains\AbstractInputValidator;

final class SavePasswordValidator extends AbstractInputValidator
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::rules()
     */
    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:8|confirmed',
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
            'passord.required' => 'Hasło jest wymagane',
            'password.min' => 'Hasło jest za krótkie (min. 8 znaków)',
            'password.confirmed' => 'Podane hasła się różnią'
        ];
    }
}
