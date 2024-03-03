<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\BookClient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class BookRentalResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var BookClient
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
        $bookClient = $this->resource;

        /** @var Book */
        $book = Cache::remember(
            "books.{$bookClient->book_id}",
            now()->addHours(2),
            fn () => $bookClient->book
        );

        return [
            'id' => $book->id,
            'name' => $book->name,
            'rent_started_at' => $bookClient->rent_started_at,
            'rent_ended_at' => $bookClient->rent_ended_at,
        ];
    }
}
