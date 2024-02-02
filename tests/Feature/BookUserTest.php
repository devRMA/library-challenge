<?php

use App\Models\Book;
use App\Models\BookUser;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the list of rents', function () {
    $user = User::factory()->create();

    foreach (Book::factory()->count(3)->create() as $book) {
        $user->books()->attach($book, [
            'dt_aluguel_ini' => fake()->date(),
            'dt_aluguel_fim' => fake()->date(),
            'dt_inclusao' => now(),
            'dt_alteracao' => now(),
        ]);
    }

    getJson(route('book-users.index'))
        ->assertOk()
        ->assertJsonCount(3);
});

it('should allow the creation of new rent', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();

    postJson(route('book-users.store'), [
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => now()->subDays(5)->toDateTimeString(),
        'dt_aluguel_fim' => now()->addDays(5)->toDateTimeString(),
    ])
        ->assertCreated();

    assertDatabaseHas(BookUser::class, [
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
    ]);
});

it('should return error 409 if the book is already being rented', function () {
    $book = Book::factory()->create();
    $book2 = Book::factory()->create();
    $user = User::factory()->create();
    BookUser::query()->create([
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => now()->subDays(2),
        'dt_aluguel_fim' => now()->addDays(1),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);
    BookUser::query()->create([
        'livro_id' => $book2->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => now()->subDays(3),
        'dt_aluguel_fim' => now()->subDays(1),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    postJson(route('book-users.store'), [
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => now()->toDateTimeString(),
        'dt_aluguel_fim' => now()->addDay()->toDateTimeString(),
    ])
        ->assertConflict();
    postJson(route('book-users.store'), [
        'livro_id' => $book2->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => now()->toDateTimeString(),
        'dt_aluguel_fim' => now()->addDay()->toDateTimeString(),
    ])
        ->assertCreated();
});

it('should return rental data by id', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();
    $rent = BookUser::query()->create([
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => fake()->date(),
        'dt_aluguel_fim' => fake()->date(),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    getJson(route('book-users.show', $rent->id))
        ->assertOk()
        ->assertJson([
            'id' => $rent->id,
            'livro_id' => $book->id,
            'usuario_id' => $user->id,
        ]);
});

it('should allow updating rental information', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();
    $rent = BookUser::query()->create([
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => fake()->date(),
        'dt_aluguel_fim' => fake()->date(),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);
    $newData = [
        'dt_aluguel_ini' => now()->subDays(6)->toDateTimeString(),
        'dt_aluguel_fim' => now()->addDays(7)->toDateTimeString(),
    ];

    putJson(route('book-users.update', $rent->id), $newData)
        ->assertNoContent();

    assertDatabaseHas(BookUser::class, [
        'id' => $rent->id,
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => $newData['dt_aluguel_ini'],
        'dt_aluguel_fim' => $newData['dt_aluguel_fim'],
    ]);
});

it('should allow the exclusion of rentals', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();
    $rent = BookUser::query()->create([
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => fake()->date(),
        'dt_aluguel_fim' => fake()->date(),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    deleteJson(route('book-users.destroy', $rent->id))
        ->assertNoContent();

    assertDatabaseMissing(BookUser::class, [
        'id' => $rent->id,
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => $rent->dt_aluguel_ini,
        'dt_aluguel_fim' => $rent->dt_aluguel_fim,
    ]);
});
