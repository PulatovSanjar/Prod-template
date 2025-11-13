<?php
declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Foundation\Http\FormRequest;

interface FormActionContract
{
    /**
     * @param FormRequest $request
     * @return mixed
     */
    public function handle(FormRequest $request): mixed;
}
