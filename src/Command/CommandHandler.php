<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Fetch;
use ADB\ImmoSyncWhise\Command\Sync;
use ADB\ImmoSyncWhise\Container;

class CommandHandler
{
    private $commands = [];

    public function __construct(Container $container)
    {
        if (defined('WP_CLI') && WP_CLI) {
            $this->commands = array_map(
                function ($className) use ($container) {
                    return \WP_CLI::add_command($className::COMMAND_NAME, new $className($container));
                },
                [
                    // Import::class,
                    Fetch::class,
                    // Sync::class,
                ]
            );
        }
    }
}
