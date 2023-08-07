<?php

/*
 * Plugin Name:       Immo Sync Whise
 * Plugin URI:        
 * Description:       Integrates the Whise API with WordPress
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.3
 * Author:            De Belser Arne
 * Author URI:        
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       immo-sync-whise
 * Domain Path:       /languages
 */

require_once __DIR__ . '/vendor/autoload.php';

use ADB\ImmoSyncWhise\Database\CreateIwsDetailsTable;
use ADB\ImmoSyncWhise\Plugin;
use Dotenv\Dotenv;

define('ISW_VERSION', fileatime(__DIR__));
define('ISW_PATH', __DIR__);
define('PLUGIN__FILE__', __FILE__);

/*
$dotenv_file = __DIR__ . '/.env';

try {
    if (!file_exists($dotenv_file)) {
        throw new Exception('.env file not found');
    }

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    // Display a WordPress error notice
    add_action('admin_notices', 'display_missing_env_file_notice');

    function display_missing_env_file_notice()
    {
        echo '<div class="error"><p><strong>Warning:</strong> The ".env" file is missing. Please create it to configure the environment.</p></div>';
    }
}
*/

new CreateIwsDetailsTable();

$immo_sync_whise = new Plugin();
