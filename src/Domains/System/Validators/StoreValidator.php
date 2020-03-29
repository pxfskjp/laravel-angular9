<?php

namespace App\Domains\System\Validators;

use App\Domains\AbstractInputValidator;

final class StoreValidator extends AbstractInputValidator
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::rules()
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
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
            ];
    }

}
