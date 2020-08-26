<?php

namespace App\Domains\User\Validators;

use App\Domains\AbstractInputValidator;
use Illuminate\Validation\Rule;

final class UpdateValidator extends AbstractInputValidator
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::rules()
     */
    protected function rules(): array
    {
        return [
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->input['id'], 'id')
            ],
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
            'lastname.required' => 'Lastname jest wymagane',
            'lastname.min' => 'Lastname jest zbyt krótkie (min. 3 znaki)',
            'lastname.max' => 'Lastname jest zbyt długie (max. 15 znaków)',
            'email.required' => 'Email jest wymagany',
            'email.email' => 'To nie jest prawidłowy adres email',
            'email.max' => 'Email jest zbyt długi (max. 255 znaków)',
            'email.unique' => 'Taki email już istnieje',
        ];
    }
}
