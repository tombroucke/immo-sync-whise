<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Exceptions\CredentialsNotSetException;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\Exception\AuthException;
use ADB\ImmoSyncWhise\Vendor\Whise\Api\WhiseApi;

class Api
{
    public WhiseApi $connection;

    public function __construct(WhiseApi $connection, string $whiseUser, string $whisePassword, int $clientId = null)
    {
        $this->connection = $connection;

        try {
            if (
                empty($whiseUser) ||
                empty($whisePassword)
            ) {
                throw new CredentialsNotSetException;
            }

            $accessToken = $this->connection->requestAccessToken($whiseUser, $whisePassword);
            $this->connection->setAccessToken($accessToken);

            if ($clientId) {
                $clientToken = $this->connection->requestClientToken($clientId);
                $this->connection->setAccessToken($clientToken);
            }
        } catch (AuthException $e) {
            add_action('admin_notices', [$this, 'unauthorizedMessage']);
        } catch (CredentialsNotSetException $e) {
            add_action('admin_notices', [$this, 'unauthorizedMessageDatabase']);
        }
    }

    public function unauthorizedMessage(): void
    {
?>
        <div class="notice notice-error">
            <p><?php _e('<h3>Immo Sync Whise: Unauthorized Access</h3><span>To enable proper functionality of the Whise integration, please ensure you provide the correct login credentials in the .env file.</span>', 'immo-sync-whise'); ?></p>
        </div>
    <?php
    }

    public function unauthorizedMessageDatabase(): void
    {
    ?>
        <div class="notice notice-error">
            <p>
                <?php
                printf(
                    __('<h3>Immo Sync Whise: Missing Credentials</h3><span>Please set up your Whise credentials in the <a href="%s">plugin settings</a> to proceed.</span>', 'immo-sync-whise'),
                    esc_url(admin_url('options-general.php?page=immo-sync-whise-settings'))
                );
                ?>
            </p>
        </div>
    <?php
    }
}
