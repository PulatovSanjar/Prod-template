<?php

namespace App\Http\Controllers\Traits;

use Flasher\Prime\FlasherInterface;

trait HasNotificationTrait
{
    /**
     * @param FlasherInterface $flasher
     */
    function __construct(private FlasherInterface $flasher)
    {
    }

    /**
     * @return FlasherInterface
     */
    protected function notify(): FlasherInterface
    {
        return $this->flasher;
    }
}
