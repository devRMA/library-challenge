<?php

use App\Models\Book;
use App\Models\BookClient;
use App\Models\Client;

it('should associate a BookClient with the book and the client', function () {
    $book = Book::factory()->create();
    $client = Client::factory()->create();

    $pivot = BookClient::query()->create([
        'book_id' => $book->id,
        'client_id' => $client->id,
        'rent_started_at' => now()->subDays(5),
        'rent_ended_at' => now(),
    ]);

    expect($pivot->book()->first())
        ->toBeInstanceOf(Book::class)
        ->name->toBe($book->name);
    expect($pivot->client()->first())
        ->toBeInstanceOf(Client::class)
        ->cpf->toBe($client->cpf);
});
