<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Services\EstateSyncService;

class FetchAll
{
    public const COMMAND_NAME = 'iws fetch-all';

    public function __construct(private EstateSyncService $estateSyncService)
    {
    }

    /**
     * Run the command:
     * 
     * wp iws fetch-all handle
     */
    public function handle(): void
    {
        $this->estateSyncService->syncAll();
    }
}
