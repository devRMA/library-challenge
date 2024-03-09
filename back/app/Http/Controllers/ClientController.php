<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientsRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\ClientResource;
use App\Models\Book;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ClientController extends Controller
{
    /**
     * Retorna a lista com os clientes cadastrados.
     *
     * @param ClientsRequest $request
     *
     * @return JsonResponse
     */
    public function index(ClientsRequest $request): JsonResponse
    {
        return response()
            ->json(
                ClientResource::collection(
                    Client::search($request->validated('query'))
                        ->get()
                )
            );
    }

    /**
     * Realiza o cadastro de um novo cliente.
     *
     * @param StoreClientRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        Client::query()->create([
            'name' => $request->validated('name'),
            'cpf' => $request->validated('cpf'),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Retorna os dados de um cliente específico.
     *
     * @param Client $client
     *
     * @return JsonResponse
     */
    public function show(Client $client): JsonResponse
    {
        return response()
            ->json(
                new ClientResource($client)
            );
    }

    /**
     * Atualiza os dados de um cliente específico.
     *
     * @param UpdateClientRequest $request
     * @param Client              $client
     *
     * @return JsonResponse
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update([
            'name' => $request->validated('name'),
            'cpf' => $request->validated('cpf'),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Deleta um cliente específico.
     *
     * @param Client $client
     *
     * @return JsonResponse
     */
    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()
            ->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Retorna o histórico de aluguéis de um cliente específico.
     *
     * @param Client $client
     *
     * @return JsonResponse
     */
    public function rentalHistory(Client $client): JsonResponse
    {
        return response()
            ->json(
                BookResource::collection(
                    $client->books()
                        ->orderBy('rent_ended_at', 'desc')
                        ->get()
                )
            );
    }

    /**
     * Realiza o aluguel de um livro para um cliente específico.
     *
     * @param Client $client
     * @param Book   $book
     *
     * @return JsonResponse
     */
    public function rentBook(Client $client, Book $book): JsonResponse
    {
        // se o livro já estiver sendo alugado
        if ($book->clients()->wherePivot('rent_ended_at', null)->exists()) {
            $message = __('validation.unique', ['attribute' => 'book']);

            return response()
                ->json([
                    'message' => $message,
                    'errors' => [
                        'book' => [
                            $message,
                        ],
                    ],
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $client->books()->attach($book, [
            'rent_started_at' => now(),
        ]);
        Cache::forget('book_'.$book->id.'_available');

        return response()
            ->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Registra a devolução de um livro alugado por um cliente.
     *
     * @param Client $client
     * @param Book   $book
     *
     * @return JsonResponse
     */
    public function returnBook(Client $client, Book $book): JsonResponse
    {
        // se o cliente não estiver alugando o livro
        if (!$client->books()->wherePivot('book_id', $book->id)->wherePivot('rent_ended_at', null)->exists()) {
            return response()
                ->json('', JsonResponse::HTTP_NOT_FOUND);
        }

        $client->books()->updateExistingPivot($book, [
            'rent_ended_at' => now(),
        ]);
        Cache::forget('book_'.$book->id.'_available');

        return response()
            ->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
