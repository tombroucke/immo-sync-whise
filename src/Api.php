<?php

namespace ADB\ImmoSyncWhise;

use Whise\Api\WhiseApi;

class Api
{
    public WhiseApi $connection;

    public function __construct(WhiseApi $connection, $whiseUser, $whisePassword)
    {
        $this->connection = $connection;

        $accessToken = $this->connection->requestAccessToken($whiseUser, $whisePassword);

        $this->connection->setAccessToken($accessToken);
    }
}
