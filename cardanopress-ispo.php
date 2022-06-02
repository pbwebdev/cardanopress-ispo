<?php

/**
 * Plugin Name: CardanoPress - ISPO
 * Plugin URI:  https://github.com/pbwebdev/cardanopress-ispo
 * Author:      Gene Alyson Fortunado Torcende
 * Author URI:  https://pbwebdev.com
 * Description: A CardanoPress extension for ISPO
 * Version:     0.1.0
 * License:     GPL-2.0-only
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package ThemePlate
 * @since   0.1.0
 */

// Accessed directly
if (! defined('ABSPATH')) {
    exit;
}

use PBWebDev\CardanoPress\ISPO\Application;
use PBWebDev\CardanoPress\ISPO\Installer;

/* ==================================================
Global constants
================================================== */

if (! defined('CP_ISPO_FILE')) {
    define('CP_ISPO_FILE', __FILE__);
}

// Load the main plugin class
require_once plugin_dir_path(CP_ISPO_FILE) . 'vendor/autoload.php';

// Instantiate the updater
EUM_Handler::run(CP_ISPO_FILE, 'https://raw.githubusercontent.com/pbwebdev/cardanopress-ispo/main/update-data.json');

// Instantiate
function cpISPO(): Application
{
    static $application;

    if (null === $application) {
        $application = new Application(CP_ISPO_FILE);
    }

    return $application;
}

cpISPO()->setupHooks();
(new Installer(cpISPO()))->setupHooks();