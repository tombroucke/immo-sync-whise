<?php declare(strict_types=1);

/*
 * This file is part of the ADB\ImmoSyncWhise\Vendor\Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADB\ImmoSyncWhise\Vendor\Monolog\Formatter;

use ADB\ImmoSyncWhise\Vendor\Monolog\Utils;
use ADB\ImmoSyncWhise\Vendor\Monolog\LogRecord;

/**
 * Class FluentdFormatter
 *
 * Serializes a log message to Fluentd unix socket protocol
 *
 * Fluentd config:
 *
 * <source>
 *  type unix
 *  path /var/run/td-agent/td-agent.sock
 * </source>
 *
 * ADB\ImmoSyncWhise\Vendor\Monolog setup:
 *
 * $logger = new ADB\ImmoSyncWhise\Vendor\Monolog\Logger('fluent.tag');
 * $fluentHandler = new ADB\ImmoSyncWhise\Vendor\Monolog\Handler\SocketHandler('unix:///var/run/td-agent/td-agent.sock');
 * $fluentHandler->setFormatter(new ADB\ImmoSyncWhise\Vendor\Monolog\Formatter\FluentdFormatter());
 * $logger->pushHandler($fluentHandler);
 *
 * @author Andrius Putna <fordnox@gmail.com>
 */
class FluentdFormatter implements FormatterInterface
{
    /**
     * @var bool $levelTag should message level be a part of the fluentd tag
     */
    protected bool $levelTag = false;

    /**
     * @throws \RuntimeException If the function json_encode does not exist
     */
    public function __construct(bool $levelTag = false)
    {
        if (!function_exists('json_encode')) {
            throw new \RuntimeException('PHP\'s json extension is required to use ADB\ImmoSyncWhise\Vendor\Monolog\'s FluentdUnixFormatter');
        }

        $this->levelTag = $levelTag;
    }

    public function isUsingLevelsInTag(): bool
    {
        return $this->levelTag;
    }

    public function format(LogRecord $record): string
    {
        $tag = $record->channel;
        if ($this->levelTag) {
            $tag .= '.' . $record->level->toPsrLogLevel();
        }

        $message = [
            'message' => $record->message,
            'context' => $record->context,
            'extra' => $record->extra,
        ];

        if (!$this->levelTag) {
            $message['level'] = $record->level->value;
            $message['level_name'] = $record->level->getName();
        }

        return Utils::jsonEncode([$tag, $record->datetime->getTimestamp(), $message]);
    }

    public function formatBatch(array $records): string
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}
