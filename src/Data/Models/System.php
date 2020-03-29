<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Data\Models\System
 *
 * @property int $id
 * @property string $name
 * @property \DateTime $deleted_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */

final class System extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'systems';

    /**
     * @var array $attributes
     */
    protected $attributes = [
      'id' => null,
      'name' => null,
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
      'id' => 'integer',
      'name' => 'string',
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
    ];
}
