<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Retorna a lista de usuários.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()
            ->json(
                UserResource::collection(User::all())
            );
    }

    /**
     * Cadastrar um novo usuário.
     *
     * @param StoreUserRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        User::query()->create([
            'nome' => $request->validated('nome'),
            'cpf' => $request->validated('cpf'),
            'dt_inclusao' => now(),
            'dt_alteracao' => now(),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Mostra detalhes de um usuário.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()
            ->json(
                new UserResource($user->load('books'))
            );
    }

    /**
     * Atualiza um usuário.
     *
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update([
            'nome' => $request->validated('nome'),
            'cpf' => $request->validated('cpf'),
            'dt_alteracao' => now(),
        ]);

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Remove um usuário.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        // TODO : Tratar quando o usuário tiver livros alugados
        $user->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
