<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements \Laravel\Passport\Contracts\OAuthenticatable
{
    use Notifiable, HasApiTokens, HasRoles, \Illuminate\Database\Eloquent\Factories\HasFactory;

    public const ADMIN_ROLES = ['Admin', 'admin'];
    public const SUBADMIN_ROLE = 'Subadministrador';
    public const SELLER_ROLE = 'Vendedor';

    public function isSalesAdministrator(): bool
    {
        return $this->hasRole(self::ADMIN_ROLES, 'web');
    }

    public function canAccessSales(): bool
    {
        return $this->hasRole([...self::ADMIN_ROLES, self::SUBADMIN_ROLE, self::SELLER_ROLE], 'web');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
