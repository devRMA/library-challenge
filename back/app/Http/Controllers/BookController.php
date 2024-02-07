<?php

namespace App\Http\Controllers;

use App\Http\Requests\BooksRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Retorna a lista com os livros cadastrados.
     *
     * @param BooksRequest $request
     *
     * @return JsonResponse
     */
    public function index(BooksRequest $request): JsonResponse
    {
        return response()
            ->json(
                BookResource::collection(
                    Book::search($request->validated('query'))
                        ->get()
                )
            );
    }

    /**
     * Realiza o cadastro de um novo livro.
     *
     * @param StoreBookRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        Book::query()->create([
            'name' => $request->validated('name'),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_CREATED);
    }

    /**
     * Retorna os dados de um livro específico.
     *
     * @param Book $book
     *
     * @return JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        return response()
            ->json(
                new BookResource($book)
            );
    }

    /**
     * Atualiza os dados de um livro específico.
     *
     * @param UpdateBookRequest $request
     * @param Book              $book
     *
     * @return JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update([
            'name' => $request->validated('name'),
        ]);

        return response()
            ->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Deleta um livro específico.
     *
     * @param Book $book
     *
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()
            ->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
