<?php

namespace App\Data\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Data\Models\User\Hardware
 *
 * @property int $user_id*
 * @property int $hardware_id
 */

final class Hardware extends Model
{
    /**
     *
     * @var boolean $timestamps
     */
    public $timestamps = false;

    /**
     *
     * @var boolean $incrementing
     */
    public $incrementing = false;

    /**
     * @var string $table
     */
    protected $table = 'user_hardwares';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'hardware_id',
    ];
}
