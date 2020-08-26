<?php

namespace App\Domains\Hardware\Validators;

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
            'system_id' => 'nullable|int',
            'name' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100',
            'production_year' => 'required',
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::messages()
     */
    protected function messages(): array
    {
        return [];
    }
}
