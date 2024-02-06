<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int                                                               $id
 * @property null|string                                                       $name
 * @property string                                                            $email
 * @property \Illuminate\Database\Eloquent\Collection<int,\App\Models\Session> $sessions
 * @property Carbon                                                            $created_at
 * @property Carbon                                                            $updated_at
 * @property null|Illuminate\Database\Eloquent\Relations\Pivot                 $pivot
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    // @see https://rappasoft.com/docs/laravel-authentication-log/v1/start/configuration#content-setting-up-your-model
    use AuthenticationLoggable;
    // @see https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events
    use LogsActivity;

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
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Configuração do que será registrado nos logs.
     *
     * @see https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logOnly([
                'name',
                'email',
            ]);
    }

    /**
     * As sessões do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Session>
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
