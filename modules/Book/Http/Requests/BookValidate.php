<?php

namespace Modules\Book\Http\Requests;

use Modules\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
class BookValidate extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'author_id' => 'required',
            'edition' => 'nullable',
            'volumes' => 'nullable|numeric',
            'pages' => 'nullable|numeric',
            'source_of_fund' => 'nullable',
            'publisher' => 'nullable',
            'publication_year' => 'nullable',
            'genre' => 'nullable',
            'access_book_number' => [
                'required',
                Rule::unique('books')->ignore($this->route('id')),
            ],
            'cover_image' => 'nullable|image|max:2048', // Max size 2MB
            'quantity' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'author_id.required' => 'The author field is required.',
        ];
    }
}
