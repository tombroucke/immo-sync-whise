<?php

namespace ADB\ImmoSyncWhise\Services;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
use ADB\ImmoSyncWhise\Services\Contracts\ServiceContract;
use ADB\ImmoSyncWhise\Vendor\Psr\Log\LoggerInterface;

class TestService implements ServiceContract
{
    public function __construct(
        private Estate $estate,
        private EstateAdapter $estateAdapter,
        private EstateParser $estateParser,
        public  LoggerInterface $logger,
    ) {
    }

    public function run($args, $assocArgs): void
    {
        \WP_CLI::log("Running TestCommand");
        $this->logger->info("Running TestCommand");

        \WP_CLI::success('Test successful');
        $this->logger->info("Test successful");
    }
}
