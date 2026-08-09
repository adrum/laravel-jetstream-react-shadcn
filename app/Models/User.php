<?php

namespace App\Models;

use Database\Factories\UserFactory;
/* @chisel-email-verification */
use Illuminate\Contracts\Auth\MustVerifyEmail;
/* @end-chisel-email-verification */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
/* @chisel-passkeys */
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
/* @end-chisel-passkeys */
/* @chisel-2fa */
use Laravel\Fortify\TwoFactorAuthenticatable;
/* @end-chisel-2fa */
/* @chisel-profile-photos */
use Laravel\Jetstream\HasProfilePhoto;
/* @end-chisel-profile-photos */
/* @chisel-teams */
use Laravel\Jetstream\HasTeams;
/* @end-chisel-teams */
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /* @chisel-api */
    use HasApiTokens;
    /* @end-chisel-api */

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    /* @chisel-profile-photos */
    use HasProfilePhoto;
    /* @end-chisel-profile-photos */

    /* @chisel-teams */
    use HasTeams;
    /* @end-chisel-teams */

    use Notifiable;

    /* @chisel-passkeys */
    use PasskeyAuthenticatable;

    /* @end-chisel-passkeys */
    /* @chisel-2fa */
    use TwoFactorAuthenticatable;
    /* @end-chisel-2fa */

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        /* @chisel-2fa */
        'two_factor_recovery_codes',
        'two_factor_secret',
        /* @end-chisel-2fa */
    ];

    /* @chisel-profile-photos */
    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    /* @end-chisel-profile-photos */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
