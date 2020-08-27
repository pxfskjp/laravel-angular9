<?php

namespace App\Data\Models\User;

use Illuminate\Support\Facades\Hash;

trait HasMutators
{

    /**
     *
     * @param string $value
     */
    public function setPasswordAttribute(?string $value): void
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
