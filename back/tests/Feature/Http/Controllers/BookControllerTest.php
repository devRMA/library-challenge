<?php

use App\Models\Book;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

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
        ->getJson(route('books.show', $book->id))
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
        ->putJson(route('books.update', $book->id), $newData)
        ->assertNoContent();

    assertDatabaseHas(Book::class, $newData);
});

it('should allow deleting books', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    actingAs($user)
        ->deleteJson(route('books.destroy', $book->id))
        ->assertNoContent();

    assertDatabaseMissing(Book::class, [
        'id' => $book->id,
        'name' => $book->name,
    ]);
});
