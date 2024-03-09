<?php

namespace App\Http\Resources;

use App\Models\BookClient;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Client
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
        $client = $this->resource;

        return [
            'id' => $client->id,
            'name' => $client->name,
            'cpf' => $client->cpf,
            'rent_started_at' => $this->whenPivotLoaded(
                new BookClient(),
                fn () => $client->pivot->rent_started_at
            ),
            'rent_ended_at' => $this->whenPivotLoaded(
                new BookClient(),
                fn () => $client->pivot->rent_ended_at
            ),
        ];
    }
}
