<?php

namespace ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Cache;

interface Factory
{
    /**
     * Get a cache store instance by name.
     *
     * @param  string|null  $name
     * @return \ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Cache\Repository
     */
    public function store($name = null);
}
