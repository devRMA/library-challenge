<?php

use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the list of users', function () {
    User::factory()->count(3)->create();

    getJson(route('users.index'))
        ->assertOk()
        ->assertJsonCount(3);
});

it('should allow the creation of new users', function () {
    $user = User::factory()->make();

    postJson(route('users.store'), [
        'nome' => $user->nome,
        'cpf' => $user->cpf,
    ])
        ->assertCreated();

    assertDatabaseHas(User::class, [
        'nome' => $user->nome,
        'cpf' => $user->cpf,
    ]);
});

it('should return error 422 if the cpf has already been used', function () {
    $user = User::factory()->create();

    postJson(route('users.store'), [
        'nome' => fake()->name(),
        'cpf' => $user->cpf,
    ])
        ->assertInvalid(['cpf']);
});

it("should return a user's data by id", function () {
    $user = User::factory()->create();

    getJson(route('users.show', $user->id))
        ->assertOk()
        ->assertJson([
            'id' => $user->id,
            'nome' => $user->nome,
            'cpf' => $user->cpf,
        ]);
});

it('should allow the update of a user', function () {
    $user = User::factory()->create();

    $newData = User::factory()->make()->toArray();

    putJson(route('users.update', $user->id), $newData)
        ->assertNoContent();

    assertDatabaseHas(User::class, $newData);
});

it('should allow deleting users', function () {
    $user = User::factory()->create();

    deleteJson(route('users.destroy', $user->id))
        ->assertNoContent();

    assertDatabaseMissing(User::class, [
        'nome' => $user->nome,
        'cpf' => $user->cpf,
    ]);
});
