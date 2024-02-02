<?php

use App\Models\Book;
use App\Models\BookUser;
use App\Models\User;

it('should associate a BookUser with the book and the user', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();

    $pivot = BookUser::query()->create([
        'livro_id' => $book->id,
        'usuario_id' => $user->id,
        'dt_aluguel_ini' => fake()->date(),
        'dt_aluguel_fim' => fake()->date(),
        'dt_inclusao' => now(),
        'dt_alteracao' => now(),
    ]);

    expect($pivot->book()->first())
        ->toBeInstanceOf(Book::class);
    expect($pivot->user()->first())
        ->toBeInstanceOf(User::class);
});
