<?php

namespace Askedio\MultiAuth\Contracts;

interface Provider
{
    /**
     * Process a callback.
     *
     * @return
     */
    public function callfront();

    public function callback();
}
