<?php

namespace Modules\User\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Modules\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UserValidate extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('id'))
            ],
            'password' => $this->passwordRules(),
            'profile_type' => 'string',
            'profile_id' => 'integer|numeric',
        ];
    }

    private function passwordRules()
    {
        $rules = [Password::min(8)];

        if (request()->isMethod('post')) {
            array_unshift($rules, ['required']);
        }

        if (request()->isMethod('put') and request()->isEmptyString('password')) {
            return [];
        }

        return $rules;
    }
}
