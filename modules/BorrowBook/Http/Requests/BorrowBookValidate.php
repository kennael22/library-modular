<?php

namespace Modules\BorrowBook\Http\Requests;

use Modules\Support\Http\Requests\Request;

class BorrowBookValidate extends Request
{
    public function rules(): array
    {
        return [
            'book_id' => 'required',
            'member_id' => 'required',
            'borrow_date' => 'required',
            'due_date' => 'required',
            'return_date' => 'nullable'
        ];
    }
}
