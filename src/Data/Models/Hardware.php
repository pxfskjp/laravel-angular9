<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Data\Models\Hardware
 *
 * @property int $id
 * @property int $system_id
 * @property string $name
 * @property string $serial_number
 * @property int $production_year
 * @property \DateTime $deleted_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */

final class Hardware extends Model
{

    /**
     * @var string $table
     */
    protected $table = 'hardwares';

    /**
     *
     * @var array $attributes
     */
    protected $attributes = [
        'id' => null,
        'name' => null,
        'serial_number' => null,
        'production_year' => null
    ];

    /**
     * @var array $dates
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'serial_number',
        'production_year',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     *
     * @return HasOne
     */
    public function system(): HasOne
    {
        return $this->hasOne('App\Data\Models\Hardware\System');
    }

    /**
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Data\Models\User\Hardware', 'id', 'hardware_id');
    }
}
