<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Contracts\CommandContract;
use ADB\ImmoSyncWhise\Services\EstateSyncService;

class SyncCommand implements CommandContract
{
    public const COMMAND_NAME = 'iws sync';

    public function __construct(private EstateSyncService $estateSyncService)
    {
    }

    /**
     * Run below command to activate:
     *
     * wp iws sync-today handle
     */
    public function handle($args, $assocArgs): void
    {
        $this->estateSyncService->run($args, $assocArgs);
    }
}
