<?php

namespace App\Data\Models\User;

use App\Data\Models\Hardware;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasRelations
{

    /**
     *
     * @return HasOne
     */
    public function hardware(): HasOne
    {
        return $this->hasOne(Hardware::class, 'id', 'hardware_id');
    }
}
