<?php

namespace Modules\Author\Http\Requests;

use Modules\Support\Http\Requests\Request;

class AuthorValidate extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'suffix_name' => 'nullable',
        ];
    }
}
