<?php

namespace ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Auth;

interface PasswordBrokerFactory
{
    /**
     * Get a password broker instance by name.
     *
     * @param  string|null  $name
     * @return \ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null);
}
