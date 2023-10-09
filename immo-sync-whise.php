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

use ADB\ImmoSyncWhise\Database\CreateDetailsTable;
use ADB\ImmoSyncWhise\Plugin;

define('ISW_VERSION', fileatime(__DIR__));
define('ISW_PATH', __DIR__);
define('PLUGIN__FILE__', __FILE__);

new CreateDetailsTable();

$immo_sync_whise = new Plugin();
