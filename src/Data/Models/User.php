<?php

namespace App\Data\Models;

use App\Data\Models\User\HasMutators;
use App\Data\Models\User\HasRelations;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
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
    use HasRelations;
    use HasMutators;

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
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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
