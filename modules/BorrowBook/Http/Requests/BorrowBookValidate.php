<?php

namespace Modules\BorrowBook\Http\Requests;

use Modules\Support\Http\Requests\Request;

class BorrowBookValidate extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
