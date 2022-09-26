<?php

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
