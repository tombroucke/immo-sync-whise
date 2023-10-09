<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Fetch;
use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Services\EstateSyncService;

class CommandHandler
{
    public function __construct(public Container $container)
    {
        if (defined('WP_CLI') && WP_CLI) {
            \WP_CLI::add_command(Fetch::COMMAND_NAME, new Fetch($this->container->get(EstateSyncService::class)));

            // array_map(
            //     function ($className, $params) use ($container) {
            //         dd($params);
            //         return \WP_CLI::add_command(
            //             $className::COMMAND_NAME,
            //             function () use ($className, $container, $params) {
            //                 return new $className($container->get('operations'), ...$params);
            //             }
            //         );
            //     },
            //     [
            //         Fetch::class => [
            //             $container->get(EstateSyncService::class)
            //         ],
            //     ]
            // );
        }
    }
}
