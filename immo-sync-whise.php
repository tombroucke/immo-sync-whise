<?php

/*
 * Plugin Name:       Immo Sync Whise
 * Plugin URI:        
 * Description:       Elevate your real estate WordPress site with the power of Whise through our Immo Sync plugin. This seamless integration simplifies the connection between your website and the Whise API, allowing you to effortlessly incorporate real-time property data and listings, enhancing your visitors' property search experience.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.3
 * Author:            De Belser Arne
 * Author URI:        https://www.arnedebelser.be/
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
