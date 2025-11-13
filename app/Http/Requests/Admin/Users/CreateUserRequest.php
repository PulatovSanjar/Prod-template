<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\Users;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'              => 'required|string|max:191',
            'email'             => 'required|email|unique:users,email',
            'password'          => ['required', new Password(6), 'confirmed'],
            'roles'             => 'required|array|min:1',
            'roles.*'           => 'required|exists:roles,id',
        ];
    }
}
