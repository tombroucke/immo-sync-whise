<?php

namespace ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Mail;

interface Factory
{
    /**
     * Get a mailer instance by name.
     *
     * @param  string|null  $name
     * @return \ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Mail\Mailer
     */
    public function mailer($name = null);
}
