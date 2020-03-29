<?php

namespace App\Domains\User\Auth\Validators;

use App\Domains\AbstractInputValidator;

final class RemindPasswordValidator extends AbstractInputValidator
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::rules()
     */
    protected function rules(): array
    {
        return [
            'email' => 'required|email|max:255|exists:users,email',
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
            'email.required' => 'Email jest wymagany',
            'email.email' => 'To nie jest prawidłowy adres email',
            'email.max' => 'Email jest zbyt długi (max. 255 znaków)',
            'email.exists' => 'Taki email nie istnieje w bazie',
        ];
    }
}
