<?php declare(strict_types=1);

/*
 * This file is part of the ADB\ImmoSyncWhise\Vendor\Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADB\ImmoSyncWhise\Vendor\Monolog\Attribute;

/**
 * A reusable attribute to help configure a class as expecting a given logger channel.
 *
 * Using it offers no guarantee: it needs to be leveraged by a ADB\ImmoSyncWhise\Vendor\Monolog third-party consumer.
 *
 * Using it with the ADB\ImmoSyncWhise\Vendor\Monolog library only has no effect at all: wiring the logger instance into
 * other classes is not managed by Monolog.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class WithMonologChannel
{
    public function __construct(
        public readonly string $channel
    ) {
    }
}
