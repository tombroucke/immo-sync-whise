<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Exceptions\CredentialsNotSetException;
use Whise\Api\Exception\AuthException;
use Whise\Api\WhiseApi;

class Api
{
    public WhiseApi $connection;

    public function __construct(WhiseApi $connection, $whiseUser, $whisePassword)
    {
        $this->connection = $connection;

        try {
            if ($this->areWhiseCredentialsSet() === false) {
                throw new CredentialsNotSetException;
            }

            $accessToken = $this->connection->requestAccessToken($whiseUser, $whisePassword);

            $this->connection->setAccessToken($accessToken);
        } catch (AuthException $e) {
            add_action('admin_notices', [$this, 'unauthorizedMessage']);
        } catch (CredentialsNotSetException $e) {
            add_action('admin_notices', [$this, 'unauthorizedMessageDatabase']);
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

    public function unauthorizedMessageDatabase()
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

    private function areWhiseCredentialsSet()
    {
        return !empty(get_option('isw_options')['whise_user']) && !empty(get_option('isw_options')['whise_password']);
    }
}
