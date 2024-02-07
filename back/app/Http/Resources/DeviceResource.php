<?php

namespace App\Http\Resources;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

class DeviceResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Session
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array<string,mixed>
     */
    public function toArray(Request $request): array
    {
        $agent = new Agent(userAgent: $this->resource->user_agent);
        $browser = $agent->browser();
        $platform = $agent->platform();
        $location = $this->resource->location;
        $lastActivity = Carbon::createFromTimestamp(
            $this->resource->last_activity
        );

        return [
            'browser' => $browser === false ? null : $browser,
            'platform' => $platform === false ? null : $platform,
            'ip_address' => $this->resource->ip_address,
            'last_activity' => $lastActivity->toIso8601String(),
            'location' => [
                'country' => $location->country,
                'city' => $location->city,
                'state' => $location->state_name,
            ],
        ];
    }
}
