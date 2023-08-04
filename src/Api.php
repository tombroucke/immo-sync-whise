<?php

namespace ADB\ImmoSyncWhise;

use Whise\Api\Exception\AuthException;
use Whise\Api\WhiseApi;

class Api
{
    public WhiseApi $connection;

    public function __construct(WhiseApi $connection, $whiseUser, $whisePassword)
    {
        $this->connection = $connection;

        try {
            $accessToken = $this->connection->requestAccessToken($whiseUser, $whisePassword);

            $this->connection->setAccessToken($accessToken);
        } catch (AuthException $e) {
            add_action('admin_notices', [$this, 'unauthorizedMessage']);
        }
    }

    public function unauthorizedMessage()
    {
?>
        <div class="notice notice-error">
            <p><?php _e('<h3>Immo Sync Whise: Unauthorized Access</h3><span>To enable proper functionality of the Whise integration, please ensure you provide the correct login credentials in the .env file.</span>', 'immo-sync-whise'); ?></p>
        </div>
<?php
    }
}
