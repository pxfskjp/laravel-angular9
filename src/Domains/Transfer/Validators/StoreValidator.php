<?php

namespace App\Domains\Transfer\Validators;

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
            'user_id' => 'required|exists:users,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'type' => 'required|int|in:1,-1',
            'date' => 'required|date_format:Y-m-d',
            'remarks' => 'nullable|string|max:255',
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
