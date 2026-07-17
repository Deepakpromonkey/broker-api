<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                // Email should not already exist as a user
                Rule::unique('users', 'email'),

                // Email should not already have a pending invitation
                Rule::unique('invitations', 'email')
                    ->whereNull('accepted_at'),
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered or has a pending invitation.',
            'role_id.exists' => 'Selected role is invalid.',
        ];
    }
}
