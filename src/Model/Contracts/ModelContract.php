<?php

namespace ADB\ImmoSyncWhise\Model\Contracts;

use Psr\Log\LoggerInterface;

interface ModelContract
{
    public function setLogger(LoggerInterface $logger): void;

    public function save(int $id): int|\WP_Error;

    public function update($model): int|\WP_Error;

    public function saveMeta($postId, $estate): void;

    public function updateMeta($postId, $estate): void;

    public function getMetaFields($postId): array;

    public function exists($providerId): bool;
}
