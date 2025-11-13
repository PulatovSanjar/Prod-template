<?php
declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Foundation\Http\FormRequest;

interface SocialiteContract
{
    /**
     * @return string
     */
    public function handle(): string;

    /**
     * @param FormRequest $request
     * @return mixed
     */
    public function callback(FormRequest $request): mixed;
}
