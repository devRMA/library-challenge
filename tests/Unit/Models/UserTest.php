<?php

use App\Models\Book;
use App\Models\BookUser;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('should create a new user', function () {
    $user = User::factory()->make();

    User::query()->create([
        'nome' => $user->nome,
        'cpf' => $user->cpf,
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    assertDatabaseHas(User::class, [
        'nome' => $user->nome,
        'cpf' => $user->cpf,
    ]);
});

it('should allow update of user', function () {
    $oldName = fake()->name();
    $newName = fake()->name();
    $user = User::factory()->create([
        'nome' => $oldName,
    ]);
    $user->update([
        'nome' => $newName,
    ]);

    assertDatabaseHas(User::class, [
        'nome' => $newName,
        'cpf' => $user->cpf,
    ]);
});

it('should allow user deletion', function () {
    $user = User::factory()->create();

    $user->delete();

    assertDatabaseMissing(User::class, [
        'cpf' => $user->cpf,
    ]);
});

it('should associate a user with books', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $user->books()->attach($book->id, [
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
