<?php

namespace App\Services;

use App\Contracts\DTOContract;
use App\Exceptions\RoleNotFound;
use App\Jobs\SendEmailVerificationJob;
use App\Models\Role;
use App\Models\User;

class RegisterService
{
    /**
     * @param DTOContract $data
     * @return User
     */
    public function register(DTOContract $data): User
    {
        /** @var User $user */
        $user = User::query()->create($data->toArray());

        if (config('app.env') !== 'local') {
            $this->sendVerification($user);
        }

        return $user;
    }

    /**
     * @param string $token
     * @return bool|int
     */
    public function verifyEmail(string $token): bool|int
    {
        $user = User::query()->where('email_verification_token', $token)->first();

        return $user->update(['email_verified_at' => now()]);
    }

    /**
     * @param User $user
     * @return void
     */
    protected function sendVerification(User $user): void
    {
        SendEmailVerificationJob::dispatch($user);
    }

    /**
     * @param User $user
     * @return void
     * @throws RoleNotFound
     */
    protected function assignDefaultRole(User $user): void
    {
        $role = Role::query()->where('key', 'customer')->first();

        if (!$role) {
            throw new RoleNotFound('Role with key = customer not found');
        }

        $user->roles()->attach($role);
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getVerifyUrl(User $user): string
    {
        $params = [
            'token' => $user->email_verification_token
        ];

        return config('front.email-verify-url') . '?' . http_build_query($params);
    }
}
