<?php

namespace App\Contracts;

interface ActionContract
{
    /**
     * @return mixed
     */
    public function handle(): mixed;
}
