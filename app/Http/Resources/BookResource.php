<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $available = false;

        if ($this->relationLoaded('users')) {
            $available = $this->users->where('pivot.dt_aluguel_fim', '>', now())->isEmpty();
        }

        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'disponivel' => $available,
        ];
    }
}
