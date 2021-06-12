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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'username'   => ['required', 'email', 'max:20', Rule::unique('users')->ignore($this->user)],
            'email'   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user),],
            'mobile'   => ['required', 'numeric', 'max:255', Rule::unique('users')->ignore($this->user)],
            'status' => 'required',
            'password' => 'nullable|min:8',
            'bio' => 'string|max:1000',
        ];
    }
}
