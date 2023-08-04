<?php

namespace Bruno\ImmoSyncWhise;

use Whise\Api\WhiseApi;

class Api
{
    public WhiseApi $connection;

    public function __construct()
    {
        $this->connection = new WhiseApi();
        $accessToken = $this->connection->requestAccessToken($_ENV['WHISE_USER'], $_ENV['WHISE_PASSWORD']);
        $this->connection->setAccessToken($accessToken);
    }
}
