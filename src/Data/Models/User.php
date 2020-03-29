<?php

namespace App\Data\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Data\Models\User
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 */

final class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable;
    use Authorizable;

    /**
     * @var string $table
     */
    protected $table = 'users';

    /**
     * @var array $dates
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
    ];

    /**
     *
     * @var array $fillable
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     *
     * @return HasOne
     */
    public function hardware(): HasOne
    {
        return $this->hasOne('App\Data\Models\User\Hardware');
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \Tymon\JWTAuth\Contracts\JWTSubject::getJWTCustomClaims()
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     *
     * {@inheritdoc}
     * @see \Tymon\JWTAuth\Contracts\JWTSubject::getJWTIdentifier()
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
}
