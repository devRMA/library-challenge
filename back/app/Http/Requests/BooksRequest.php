<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,array<int,mixed>|\Illuminate\Contracts\Validation\Rule|string>
     */
    public function rules(): array
    {
        return [
            'query' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],
        ];
    }
}
