<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Fetch;
use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Services\EstateSyncService;

class CommandHandler
{
    private $commands = [];

    public function __construct(Container $container)
    {
        if (defined('WP_CLI') && WP_CLI) {
            $this->commands = array_map(
                function ($className, $params) use ($container) {
                    return \WP_CLI::add_command(
                        $className::COMMAND_NAME,
                        function () use ($className, $container, $params) {
                            return new $className($container->get('operations'), ...$params);
                        }
                    );
                },
                [
                    Fetch::class => [$container->get(EstateSyncService::class)],
                ]
            );
        }
    }
}
