<?php
declare(strict_types=1);

namespace App\Rules\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class HasVerifyEmailRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::query()->where('email', $value)->first();

        return !is_null($user->email_verified_at);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.email_unverified');
    }
}
