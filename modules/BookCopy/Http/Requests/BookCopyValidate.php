<?php

namespace Modules\BookCopy\Http\Requests;

use Modules\Support\Http\Requests\Request;

class BookCopyValidate extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
