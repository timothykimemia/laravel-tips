<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdageUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:20', Rule::unique('users')->ignore($this->user)],
            'email'   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user),],
            'mobile'   => ['nullable', 'numeric', Rule::unique('users')->ignore($this->user)],
            'status' => ['required', 'boolean'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'receive_email' => ['nullable', 'boolean']
        ];
    }
}
