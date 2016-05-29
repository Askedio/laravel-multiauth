<?php

namespace Askedio\MultiAuth\Contracts;

interface Factory
{
    /**
     * Get provider implementation.
     *
     * @param string $driver
     *
     * @return \Askedio\MultiAuth\Contracts\Provider
     */
    public function driver($driver = null);
}
