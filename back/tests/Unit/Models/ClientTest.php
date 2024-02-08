<?php

use App\Models\Book;
use App\Models\BookClient;
use App\Models\Client;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('should create a new client', function () {
    $client = Client::factory()->make();

    Client::query()->create([
        'name' => $client->name,
        'cpf' => $client->cpf,
    ]);

    assertDatabaseHas(Client::class, [
        'name' => $client->name,
        'cpf' => $client->cpf,
    ]);
});

it('should allow update of client', function () {
    $oldName = fake()->name();
    $newName = fake()->name();
    $client = Client::factory()->create([
        'name' => $oldName,
    ]);
    $client->update([
        'name' => $newName,
    ]);

    assertDatabaseHas(Client::class, [
        'name' => $newName,
        'cpf' => $client->cpf,
    ]);
});

it('should allow client deletion', function () {
    $client = Client::factory()->create();

    $client->delete();

    assertDatabaseMissing(Client::class, [
        'cpf' => $client->cpf,
    ]);
});

it('should associate a client with books', function () {
    $client = Client::factory()->create();
    $book = Book::factory()->create();

    $client->books()->attach($book->id, [
        'rent_started_at' => now(),
        'rent_ended_at' => null,
    ]);

    assertDatabaseHas(BookClient::class, [
        'book_id' => $book->id,
        'client_id' => $client->id,
        'rent_ended_at' => null,
    ]);
});
