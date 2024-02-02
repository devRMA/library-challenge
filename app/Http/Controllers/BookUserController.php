<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookUserRequest;
use App\Http\Requests\UpdateBookUserRequest;
use App\Http\Resources\BookUserResource;
use App\Models\BookUser;
use Illuminate\Http\JsonResponse;

class BookUserController extends Controller
{
    /**
     * Retorna a lista de aluguéis.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()
            ->json(
                BookUserResource::collection(
                    BookUser::query()->with(['book', 'user'])->get()
                )
            );
    }

    /**
     * Cadastrar um novo aluguel.
     *
     * @param StoreBookUserRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreBookUserRequest $request): JsonResponse
    {
        $bookAvailable = BookUser::query()
            ->where('livro_id', $request->validated('livro_id'))
            // só irá permitir o aluguel de um livro, caso a data inicial do
            // aluguel seja maior que a data final do aluguel anterior
            ->where('dt_aluguel_fim', '>', $request->validated('dt_aluguel_ini'))
            ->doesntExist();

        if (!$bookAvailable) {
            return response()
                ->json(
                    ['message' => 'Book not available'],
                    JsonResponse::HTTP_CONFLICT
                );
        }
        BookUser::query()->create([
            'livro_id' => $request->validated('livro_id'),
            'usuario_id' => $request->validated('usuario_id'),
            'dt_aluguel_ini' => $request->validated('dt_aluguel_ini'),
            'dt_aluguel_fim' => $request->validated('dt_aluguel_fim'),
            'dt_inclusao' => now(),
            'dt_alteracao' => now(),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Mostra detalhes de um aluguel.
     *
     * @param BookUser $bookUser
     *
     * @return JsonResponse
     */
    public function show(BookUser $bookUser): JsonResponse
    {
        return response()
            ->json(
                new BookUserResource($bookUser->load(['book', 'user']))
            );
    }

    /**
     * Atualiza um aluguel.
     *
     * @param UpdateBookUserRequest $request
     * @param BookUser              $bookUser
     *
     * @return JsonResponse
     */
    public function update(UpdateBookUserRequest $request, BookUser $bookUser): JsonResponse
    {
        $bookUser->update([
            'dt_aluguel_ini' => $request->validated('dt_aluguel_ini'),
            'dt_aluguel_fim' => $request->validated('dt_aluguel_fim'),
            'dt_alteracao' => now(),
        ]);

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Remove um aluguel.
     *
     * @param BookUser $bookUser
     *
     * @return JsonResponse
     */
    public function destroy(BookUser $bookUser): JsonResponse
    {
        $bookUser->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
