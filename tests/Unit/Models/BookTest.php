<?php

use App\Models\Book;
use App\Models\BookUser;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('should create a new book', function () {
    $book = Book::factory()->make();

    Book::query()->create([
        'nome' => $book->nome,
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    assertDatabaseHas(Book::class, [
        'nome' => $book->nome,
    ]);
});

it('should allow update of book', function () {
    $oldName = fake()->name();
    $newName = fake()->name();
    $book = Book::factory()->create([
        'nome' => $oldName,
    ]);
    $book->update([
        'nome' => $newName,
    ]);

    assertDatabaseHas(Book::class, [
        'nome' => $newName,
    ]);
});

it('should allow book deletion', function () {
    $book = Book::factory()->create();

    $book->delete();

    assertDatabaseMissing(Book::class, [
        'nome' => $book->nome,
    ]);
});

it('should associate a book with users', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $book->users()->attach($user->id, [
        'dt_aluguel_ini' => fake()->date(),
        'dt_aluguel_fim' => fake()->date(),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    assertDatabaseHas(BookUser::class, [
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
    ]);
});
