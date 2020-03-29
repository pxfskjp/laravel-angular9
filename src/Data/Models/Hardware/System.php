<?php

namespace App\Data\Models\Hardware;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Data\Models\Hardware\System
 *
 * @property int $hardware_id
 * @property int $software_id
 */

final class System extends Model
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
    protected $table = 'hardware_systems';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'hardware_id',
        'system_id',
    ];
}
