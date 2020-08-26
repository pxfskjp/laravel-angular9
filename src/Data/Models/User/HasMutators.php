<?php

namespace App\Data\Models\User;

use Illuminate\Support\Facades\Hash;

trait HasMutators
{

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
