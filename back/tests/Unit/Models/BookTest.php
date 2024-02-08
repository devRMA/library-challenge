<?php

use App\Models\Book;
use App\Models\BookClient;
use App\Models\Client;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('should create a new book', function () {
    $book = Book::factory()->make();

    Book::query()->create([
        'name' => $book->name,
    ]);

    assertDatabaseHas(Book::class, [
        'name' => $book->name,
    ]);
});

it('should allow update of book', function () {
    $oldName = fake()->name();
    $newName = fake()->name();
    $book = Book::factory()->create([
        'name' => $oldName,
    ]);
    $book->update([
        'name' => $newName,
    ]);

    assertDatabaseHas(Book::class, [
        'name' => $newName,
    ]);
});

it('should allow book deletion', function () {
    $book = Book::factory()->create();

    $book->delete();

    assertDatabaseMissing(Book::class, [
        'name' => $book->name,
    ]);
});

it('should associate a book with clients', function () {
    $client = Client::factory()->create();
    $book = Book::factory()->create();

    $book->clients()->attach($client->id, [
        'rent_started_at' => now(),
        'rent_ended_at' => null,
    ]);

    assertDatabaseHas(BookClient::class, [
        'book_id' => $book->id,
        'client_id' => $client->id,
        'rent_ended_at' => null,
    ]);
});
