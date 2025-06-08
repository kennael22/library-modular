<?php

namespace Modules\MemberType\Http\Requests;

use Modules\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MemberTypeValidate extends Request
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('member_types')->ignore($this->route('id')),
            ],
        ];
    }
}
