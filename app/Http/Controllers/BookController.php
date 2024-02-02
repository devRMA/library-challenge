<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Retorna a lista de livros.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()
            ->json(
                BookResource::collection(Book::query()->with('users')->get())
            );
    }

    /**
     * Cadastrar um novo livro.
     *
     * @param StoreBookRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        Book::query()->create([
            'nome' => $request->validated('nome'),
            'dt_inclusao' => now(),
            'dt_alteracao' => now(),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Mostra detalhes de um livro.
     *
     * @param Book $book
     *
     * @return JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        return response()
            ->json(
                new BookResource($book->load('users'))
            );
    }

    /**
     * Atualiza um livro.
     *
     * @param UpdateBookRequest $request
     * @param Book              $book
     *
     * @return JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update([
            'nome' => $request->validated('nome'),
            'dt_alteracao' => now(),
        ]);

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Remove um livro.
     *
     * @param Book $book
     *
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        // TODO : Tratar quando o livro estiver alugado
        $book->delete();

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
