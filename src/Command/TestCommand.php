<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Contracts\CommandContract;
use ADB\ImmoSyncWhise\Services\TestService;

class TestCommand implements CommandContract
{
    public const COMMAND_NAME = 'iws test';

    public function __construct(private TestService $testService)
    {
    }

    /**
     * Run below command to activate:
     *
     * wp iws test handle
     */
    public function handle(): void
    {
        $this->testService->run();
    }
}
