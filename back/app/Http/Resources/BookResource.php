<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\BookClient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

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
        $available = Cache::remember(
            'book_'.$book->id.'_available',
            config('cache.default_ttl'),
            fn () => BookClient::where('book_id', $book->id)
                ->whereNull('rent_ended_at')
                ->doesntExist()
        );

        return [
            'id' => $book->id,
            'name' => $book->name,
            'available' => $available,
            'rent_started_at' => $this->whenPivotLoaded(
                new BookClient(),
                fn () => $book->pivot->rent_started_at
            ),
            'rent_ended_at' => $this->whenPivotLoaded(
                new BookClient(),
                fn () => $book->pivot->rent_ended_at
            ),
        ];
    }
}
