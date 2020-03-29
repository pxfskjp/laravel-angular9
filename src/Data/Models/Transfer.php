<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Data\Models\Transfer
 *
 * @property int $id
 * @property int $user_id
 * @property int $hardware_id
 * @property int $type
 * @property \DateTime $date
 * @property string $remarks
 */

final class Transfer extends Model
{
    private const TYPE_LEASE = -1;

    private const TYPE_BACK = 1;

    /**
     *
     * @var boolean $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $table
     */
    protected $table = 'transfers';

    /**
     * @var array $casts
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d'
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'hardware_id',
        'type',
        'date',
        'remarks',
    ];

    /**
     *
     * @return int
     */
    public static function getTypeLease(): int
    {
        return  static::TYPE_LEASE;
    }

    /**
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Data\Models\User\Hardware');
    }

    /**
     *
     * @return bool
     */
    public function isLeaseType(): bool
    {
        return $this->type === static::getTypeLease();
    }
}
