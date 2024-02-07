<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientsRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;

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
}
