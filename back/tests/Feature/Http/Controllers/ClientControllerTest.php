<?php

use App\Models\Book;
use App\Models\Client;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

it('should return the list of clients', function () {
    $user = User::factory()->create();
    Client::factory()->count(3)->create();

    actingAs($user)
        ->getJson(route('clients.index'))
        ->assertOk()
        ->assertJsonCount(3);
});

it('should allow the search of clients', function () {
    $user = User::factory()->create();
    $client1 = Client::factory()->create();
    $client2 = Client::factory()->create();

    actingAs($user);
    getJson(route('clients.index', [
        'query' => $client1->name,
    ]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $client1->id,
            'name' => $client1->name,
            'cpf' => $client1->cpf,
        ]);
    getJson(route('clients.index', [
        'query' => $client2->cpf,
    ]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $client2->id,
            'name' => $client2->name,
            'cpf' => $client2->cpf,
        ]);
});

it('should allow the creation of new clients', function () {
    $user = User::factory()->create();
    $client = Client::factory()->make();

    actingAs($user)
        ->postJson(route('clients.store'), [
            'name' => $client->name,
            'cpf' => $client->cpf,
        ])
        ->assertCreated();

    assertDatabaseHas(Client::class, [
        'name' => $client->name,
        'cpf' => $client->cpf,
    ]);
});

it('should return error 422 if the cpf has already been used', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    actingAs($user)
        ->postJson(route('clients.store'), [
            'name' => fake()->name(),
            'cpf' => $client->cpf,
        ])
        ->assertInvalid(['cpf']);
});

it("should return a client's data by id", function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    actingAs($user)
        ->getJson(route('clients.show', $client->id))
        ->assertOk()
        ->assertJson([
            'id' => $client->id,
            'name' => $client->name,
            'cpf' => $client->cpf,
        ]);
});

it('should allow the update of a client', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $newData = Client::factory()->make()->toArray();

    actingAs($user)
        ->putJson(route('clients.update', $client->id), $newData)
        ->assertNoContent();

    assertDatabaseHas(Client::class, $newData);
});

it('should allow deleting clients', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    actingAs($user)
        ->deleteJson(route('clients.destroy', $client->id))
        ->assertNoContent();

    assertDatabaseMissing(Client::class, [
        'name' => $client->name,
        'cpf' => $client->cpf,
    ]);
});

it("should return a customer's rental history", function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    foreach (Book::factory(5)->create() as $book) {
        $client->books()->attach($book->id, [
            'rent_started_at' => now()->subDays(fake()->randomNumber(2)),
            'rent_ended_at' => fake()->boolean() ? now() : null,
        ]);
    }

    actingAs($user)
        ->getJson(route('clients.history.index', $client->id))
        ->assertOk()
        ->assertJsonCount(5)
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'rent_started_at',
                'rent_ended_at',
            ],
        ]);
})->only();
