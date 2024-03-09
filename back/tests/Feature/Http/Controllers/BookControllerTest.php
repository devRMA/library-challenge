<?php

use App\Models\Book;
use App\Models\BookClient;
use App\Models\Client;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;
use function Pest\Laravel\travel;

it('should return the list of books', function () {
    $user = User::factory()->create();
    Book::factory()->count(3)->create();

    actingAs($user)
        ->getJson(route('books.index'))
        ->assertOk()
        ->assertJsonCount(3);
});

it('should allow the search of books', function () {
    $user = User::factory()->create();
    $book1 = Book::factory()->create();
    $book2 = Book::factory()->create();

    actingAs($user);
    getJson(route('books.index', [
        'query' => $book1->name,
    ]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $book1->id,
            'name' => $book1->name,
        ]);
    getJson(route('books.index', [
        'query' => $book2->name,
    ]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $book2->id,
            'name' => $book2->name,
        ]);
});

it('should allow the creation of new books', function () {
    $user = User::factory()->create();
    $book = Book::factory()->make();

    actingAs($user)
        ->postJson(route('books.store'), [
            'name' => $book->name,
        ])
        ->assertCreated();

    assertDatabaseHas(Book::class, [
        'name' => $book->name,
    ]);
});

it("should return a book's data by id", function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    actingAs($user)
        ->getJson(route('books.show', $book))
        ->assertOk()
        ->assertJson([
            'id' => $book->id,
            'name' => $book->name,
        ]);
});

it('should allow the update of a book', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $newData = Book::factory()->make()->toArray();

    actingAs($user)
        ->putJson(route('books.update', $book), $newData)
        ->assertNoContent();

    assertDatabaseHas(Book::class, $newData);
});

it('should allow deleting books', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    actingAs($user)
        ->deleteJson(route('books.destroy', $book))
        ->assertNoContent();

    assertDatabaseMissing(Book::class, [
        'id' => $book->id,
        'name' => $book->name,
    ]);
});

it("should return a book's rental history", function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    foreach (Client::factory(5)->create() as $client) {
        $book->clients()->attach($client, [
            'rent_started_at' => now()->subDays(2),
            'rent_ended_at' => now(),
        ]);
        travel(5)->days();
    }

    actingAs($user)
        ->getJson(route('books.history.index', $book))
        ->assertOk()
        ->assertJsonCount(5)
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'cpf',
                'rent_started_at',
                'rent_ended_at',
            ],
        ]);
});

it('should indicate which books are available', function () {
    $user = User::factory()->create();
    $book1 = Book::factory()->create();
    $book2 = Book::factory()->create();
    $client = Client::factory()->create();

    $book1->clients()->attach($client, [
        'rent_started_at' => now()->subDays(2),
        'rent_ended_at' => null,
    ]);
    $book2->clients()->attach($client, [
        'rent_started_at' => now()->subDays(2),
        'rent_ended_at' => now(),
    ]);

    actingAs($user)
        ->getJson(route('books.index'))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $book1->id,
            'name' => $book1->name,
            'available' => false,
        ])
        ->assertJsonFragment([
            'id' => $book2->id,
            'name' => $book2->name,
            'available' => true,
        ]);
});

it('should delete the rentals when the book is deleted', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $client = Client::factory()->create();

    $book->clients()->attach($client, [
        'rent_started_at' => now(),
        'rent_ended_at' => now(),
    ]);

    actingAs($user)
        ->deleteJson(route('books.destroy', $book))
        ->assertNoContent();

    assertDatabaseMissing(BookClient::class, [
        'book_id' => $book->id,
        'client_id' => $client->id,
    ]);
});
