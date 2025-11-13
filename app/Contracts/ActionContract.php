<?php
declare(strict_types=1);

namespace App\Contracts;

interface ActionContract
{
    /**
     * @return mixed
     */
    public function handle(): mixed;
}
