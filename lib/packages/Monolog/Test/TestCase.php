<?php declare(strict_types=1);

/*
 * This file is part of the ADB\ImmoSyncWhise\Vendor\Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADB\ImmoSyncWhise\Vendor\Monolog\Test;

use ADB\ImmoSyncWhise\Vendor\Monolog\Level;
use ADB\ImmoSyncWhise\Vendor\Monolog\Logger;
use ADB\ImmoSyncWhise\Vendor\Monolog\LogRecord;
use ADB\ImmoSyncWhise\Vendor\Monolog\DateTimeImmutable;
use ADB\ImmoSyncWhise\Vendor\Monolog\Formatter\FormatterInterface;
use ADB\ImmoSyncWhise\Vendor\Psr\Log\LogLevel;

/**
 * Lets you easily generate log records and a dummy formatter for testing purposes
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @internal feel free to reuse this to test your own handlers, this is marked internal to avoid issues with PHPStorm https://github.com/Seldaek/monolog/issues/1677
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        if (isset($this->handler)) {
            unset($this->handler);
        }
    }

    /**
     * @param array<mixed> $context
     * @param array<mixed> $extra
     *
     * @phpstan-param value-of<Level::VALUES>|value-of<Level::NAMES>|Level|LogLevel::* $level
     */
    protected function getRecord(int|string|Level $level = Level::Warning, string|\Stringable $message = 'test', array $context = [], string $channel = 'test', \DateTimeImmutable $datetime = new DateTimeImmutable(true), array $extra = []): LogRecord
    {
        return new LogRecord(
            message: (string) $message,
            context: $context,
            level: Logger::toMonologLevel($level),
            channel: $channel,
            datetime: $datetime,
            extra: $extra,
        );
    }

    /**
     * @phpstan-return list<LogRecord>
     */
    protected function getMultipleRecords(): array
    {
        return [
            $this->getRecord(Level::Debug, 'debug message 1'),
            $this->getRecord(Level::Debug, 'debug message 2'),
            $this->getRecord(Level::Info, 'information'),
            $this->getRecord(Level::Warning, 'warning'),
            $this->getRecord(Level::Error, 'error'),
        ];
    }

    protected function getIdentityFormatter(): FormatterInterface
    {
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::any())
            ->method('format')
            ->willReturnCallback(function ($record) {
                return $record->message;
            });

        return $formatter;
    }
}
