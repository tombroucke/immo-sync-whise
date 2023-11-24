<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Contracts\CommandContract;
use ADB\ImmoSyncWhise\Services\EstateSyncDeletedService;

class SyncDeletedCommand implements CommandContract
{
    public const COMMAND_NAME = 'iws sync-deleted';

    public function __construct(private EstateSyncDeletedService $estateSyncDeletedService)
    {
    }

    /**
     * Run below command to activate:
     *
     * wp iws sync-deleted handle
     */
    public function handle($args, $assocArgs): void
    {
        $this->estateSyncDeletedService->run();
    }
}
