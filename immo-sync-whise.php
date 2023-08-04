<?php

/*
 * Plugin Name:       Immo Sync Whise
 * Plugin URI:        https://www.bruno-agency.be
 * Description:       Integrates the Whise API with WordPress
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            De Belser Arne
 * Author URI:        https://www.bruno-agency.be
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       immo-sync-whise
 * Domain Path:       /languages
 */

require_once __DIR__ . '/vendor/autoload.php';

use Bruno\ImmoSyncWhise\Database\CreateIwsDetailsTable;
use Bruno\ImmoSyncWhise\Plugin;
use Dotenv\Dotenv;

define('ISW_VERSION', fileatime(__DIR__));
define('ISW_PATH', __DIR__);
define('PLUGIN__FILE__', __FILE__);

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

new CreateIwsDetailsTable();

$immo_sync_whise = new Plugin();
