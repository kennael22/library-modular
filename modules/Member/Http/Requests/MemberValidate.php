<?php

namespace Modules\Member\Http\Requests;

use Modules\Support\Http\Requests\Request;

class MemberValidate extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            // email is unique in users and members table
            'email' => 'required|email|unique:users,email|unique:members,email,' . $this->route('id'),
            'phone' => 'required',
            'profile_image' => 'nullable|image|max:2048',
            'member_type' => 'required',
        ];
    }
}
