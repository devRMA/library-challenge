<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Torann\GeoIP\Facades\GeoIP;
use Torann\GeoIP\Location;

/**
 * @property string                                            $id
 * @property null|int                                          $user_id
 * @property null|string                                       $ip_address
 * @property null|string                                       $user_agent
 * @property string                                            $payload
 * @property int                                               $last_activity
 * @property Carbon                                            $expires_at
 * @property Location                                          $location
 * @property Carbon                                            $created_at
 * @property Carbon                                            $updated_at
 * @property null|Illuminate\Database\Eloquent\Relations\Pivot $pivot
 */
class Session extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'expires_at',
        'location',
    ];

    /**
     * Verifica se a sessão está expirada.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->last_activity < Carbon::now()->subMinutes(
            config('session.lifetime')
        )->getTimestamp();
    }

    /**
     * Obtém a data de expiração da sessão.
     *
     * @return Carbon
     */
    public function getExpiresAtAttribute(): Carbon
    {
        return Carbon::createFromTimestamp($this->last_activity)
            ->addMinutes(config('session.lifetime'));
    }

    /**
     * Obtém a localização da sessão.
     *
     * @return Location
     */
    public function getLocationAttribute(): Location
    {
        return GeoIP::getLocation($this->ip_address);
    }
}
