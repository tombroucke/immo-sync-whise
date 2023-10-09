<?php

namespace ADB\ImmoSyncWhise\Adapter;

use ADB\ImmoSyncWhise\Api;
use Whise\Api\Response\CollectionResponse;
use Whise\Api\Response\Response;

class EstateAdapter
{
    public $api;

    public function __construct(Api $api)
    {
        $this->api = $api->connection;
    }

    public function list(array $filter = [], array $sorting = [], array $fields = []): CollectionResponse
    {
        return $this->api->estates()->list($filter, $sorting, $fields);
    }

    public function get(int $id, array $filter = [], array $fields = []): Response|null
    {
        return $this->api->estates()->get($id, $filter, $fields);
    }
}
