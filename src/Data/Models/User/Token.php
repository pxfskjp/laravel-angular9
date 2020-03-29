<?php

namespace App\Data\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Data\Models\Token
 *
 * @property integer $user_id
 * @property string $secret
 * @property integer $type
 * @property \DateTime $created_at
 */
final class Token extends Model
{

    public const UPDATED_AT = null;

    private const TYPE_REGISTER = 1;

    private const TYPE_FORGOT_PASSWORD = 2;

    private const TYPE_REFRESH = 3;

    private const TYPE_ACCESS = 4;

    /**
     *
     * @var boolean $incrementing
     */
    public $incrementing = false;

    /**
     *
     * @var string $table
     */
    protected $table = 'user_tokens';

    /**
     *
     * @var array $casts
     */
    protected $casts = [
        'user_id' => 'integer',
        'secret' => 'string',
        'type' => 'integer'
    ];

    /**
     *
     * @var array $dates
     */
    protected $dates = [
        'created_at'
    ];

    /**
     *
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'secret',
        'type'
    ];

    /**
     *
     * @return int
     */
    public static function getTypeRegister(): int
    {
        return static::TYPE_REGISTER;
    }

    /**
     *
     * @return int
     */
    public static function getTypeForgotPassword(): int
    {
        return static::TYPE_FORGOT_PASSWORD;
    }

    /**
     *
     * @return int
     */
    public static function getTypeRefresh(): int
    {
        return static::TYPE_REFRESH;
    }

    /**
     *
     * @return int
     */
    public static function getTypeAccess(): int
    {
        return static::TYPE_ACCESS;
    }

    /**
     *
     * @param int $type
     * @return int
     */
    public static function getTtl(int $type): int
    {
        switch ($type) {
            case static::TYPE_REGISTER:
                $ttl = (int) \config('user.ttl.register', 1);
                break;
            case static::TYPE_FORGOT_PASSWORD:
                $ttl = (int) \config('user.ttl.forgot', 1);
                break;
            default:
                $ttl = 1;
        }
        return $ttl;
    }

    /**
     *
     * @return int
     */
    public static function getAccessTtl(): int
    {
        return (int) \config('jwt.ttl');
    }

    /**
     *
     * @return int
     */
    public static function getRefreshTtl(): int
    {
        return (int) \config('jwt.refresh_ttl');
    }

    /**
     *
     * @return string
     */
    public static function buildNewSecret(): string
    {
        return Str::uuid()->toString();
    }

    /**
     *
     * @return string
     */
    public function getJwtTokenKey(): string
    {
        return \config('jwt.secret');
    }
}
