<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Api;
use League\Container\Container as ContainerD;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class Container
{
    private static $instance = null;
    private $container;

    private function __construct()
    {
        $this->container = new ContainerD();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
            self::$instance->addDependencies();
        }

        return self::$instance;
    }

    public static function addDependencies()
    {
        $logger = (new Logger('normal_log'))
            ->pushHandler(new StreamHandler(__DIR__ . '/logs/debug.log', Level::Debug));

        $operationsLogger = (new Logger('operations'))
            ->pushHandler(new StreamHandler(__DIR__ . '/logs/operations.log', Level::Debug));

        self::getInstance()->add('logger', $logger);
        self::getInstance()->add('operations', $operationsLogger);
        self::getInstance()->add(Model\Estate::class, new Model\Estate($operationsLogger));
        self::getInstance()->add(Adapter\EstateAdapter::class, new Adapter\EstateAdapter(new Api()));
        self::getInstance()->add(Parser\EstateParser::class, new Parser\EstateParser($operationsLogger));
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
