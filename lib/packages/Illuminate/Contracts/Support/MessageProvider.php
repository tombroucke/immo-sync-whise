<?php

namespace ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Support;

interface MessageProvider
{
    /**
     * Get the messages for the instance.
     *
     * @return \ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag();
}
