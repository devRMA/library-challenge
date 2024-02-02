<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'livro_id' => [
                'required',
                'string',
                Rule::exists(Book::class, 'id'),
            ],
            'usuario_id' => [
                'required',
                'string',
                Rule::exists(User::class, 'id'),
            ],
            'dt_aluguel_ini' => [
                'required',
                'date',
            ],
            'dt_aluguel_fim' => [
                'required',
                'date',
            ],
        ];
    }
}
