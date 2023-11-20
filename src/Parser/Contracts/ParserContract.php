<?php

namespace ADB\ImmoSyncWhise\Parser\Contracts;

use ADB\ImmoSyncWhise\Vendor\Whise\Api\Response\ResponseObject;

interface ParserContract
{
    public function setMethod(string $method): void;

    public function setPostId(int $postId): void;

    public function setObject(ResponseObject $response): void;
}
