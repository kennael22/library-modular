<?php

namespace Modules\Member\Http\Requests;

use Modules\Support\Http\Requests\Request;

class MemberValidate extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
