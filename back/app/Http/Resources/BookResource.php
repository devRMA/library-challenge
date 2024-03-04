<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\BookClient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Book
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
        $book = $this->resource;
        return [
            'id' => $book->id,
            'name' => $book->name,
            'rent_started_at' => $this->whenPivotLoaded(
                new BookClient,
                fn () => $book->pivot->rent_started_at
            ),
            'rent_ended_at' => $this->whenPivotLoaded(
                new BookClient,
                fn () => $book->pivot->rent_ended_at
            ),
        ];
    }
}
