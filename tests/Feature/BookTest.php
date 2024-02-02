<?php

use App\Models\Book;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the list of books', function () {
    Book::factory()->count(3)->create();

    getJson(route('books.index'))
        ->assertOk()
        ->assertJsonCount(3);
});

it('should allow the creation of new books', function () {
    $book = Book::factory()->make();

    postJson(route('books.store'), [
        'nome' => $book->nome,
    ])
        ->assertCreated();

    assertDatabaseHas(Book::class, [
        'nome' => $book->nome,
    ]);
});

it("should return a book's data by id", function () {
    $book = Book::factory()->create();

    getJson(route('books.show', $book->id))
        ->assertOk()
        ->assertJson([
            'id' => $book->id,
            'nome' => $book->nome,
        ]);
});

it('should allow the update of a book', function () {
    $book = Book::factory()->create();

    $newData = Book::factory()->make()->toArray();

    putJson(route('books.update', $book->id), $newData)
        ->assertNoContent();

    assertDatabaseHas(Book::class, $newData);
});

it('should allow deleting books', function () {
    $book = Book::factory()->create();

    deleteJson(route('books.destroy', $book->id))
        ->assertNoContent();

    assertDatabaseMissing(Book::class, [
        'id' => $book->id,
        'nome' => $book->nome,
    ]);
});
