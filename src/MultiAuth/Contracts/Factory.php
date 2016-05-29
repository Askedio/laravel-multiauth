<?php

namespace App\MultiAuth\Contracts;

interface Factory
{
    /**
     * Get provider implementation.
     *
     * @param string $driver
     *
     * @return \App\MultiAuth\Contracts\Provider
     */
    public function driver($driver = null);
}
