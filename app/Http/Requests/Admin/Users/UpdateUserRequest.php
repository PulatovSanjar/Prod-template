<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user = $this->user->id;

        return [
            'name'              => 'required|string|max:191',
            'email'             => 'required|email|unique:users,email,' . $user . ',id',
            'email_verified_at' => 'required|boolean',
            'roles'             => 'required|array|min:1',
            'roles.*'           => 'required|exists:roles,id',
        ];
    }
}
