<?php

namespace App\Domains\User\Auth\Validators;

use App\Data\Models\User\Token;
use App\Domains\AbstractInputValidator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

final class ConfirmValidator extends AbstractInputValidator
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\AbstractInputValidator::rules()
     */
    protected function rules(): array
    {
        $now = Carbon::now();
        $now->subDays(Token::getTtl());
        return [
            'token' => [
                'required',
                'string',
                'uuid',
                Rule::exists('user_tokens', 'secret')->where(function ($query) use ($now) {
                    $query->where('type', '=', Token::getTypeRegister())
                        ->where('created_at', '>', $now);
                })
            ]
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
            'token.required' => 'Token jest wymagany',
            'token.uuid' => 'Token ma błędny format',
            'token.exists' => 'Token nie istnieje'
        ];
    }
}
