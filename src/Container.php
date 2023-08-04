<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Admin\Settings;
use ADB\ImmoSyncWhise\Api;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
use League\Container\Container as LeagueContainer;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Whise\Api\WhiseApi;

class Container
{
    private static $instance = null;

    private $container;

    private function __construct()
    {
        $this->container = new LeagueContainer();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
            self::$instance->addDependencies();
        }

        return self::$instance;
    }

    public function addDependencies()
    {
        $dependencies = [
            'logger' => (new Logger('normal_log'))->pushHandler(new StreamHandler(__DIR__ . '/logs/debug.log', Level::Debug)),
            'operations' => (new Logger('operations'))->pushHandler(new StreamHandler(__DIR__ . '/logs/operations.log', Level::Debug)),

            Estate::class => fn () => new Estate(logger: $this->container->get('logger')),
            EstateParser::class => fn () => new EstateParser(logger: $this->container->get('operations')),
            EstateAdapter::class => new EstateAdapter(
                new Api(
                    connection: new WhiseApi(),
                    whiseUser: Settings::getSetting('whise_user'),
                    whisePassword: Settings::getSetting('whise_password')
                )
            )
        ];

        foreach ($dependencies as $alias => $concrete) {
            $this->container->add($alias, $concrete);
        }
    }

    public function add(string $alias, $concrete)
    {
        $this->container->add($alias, $concrete);
    }

    public function get(string $alias)
    {
        return $this->container->get($alias);
    }

    public function make(string $alias)
    {
        return $this->container->get($alias);
    }
}
