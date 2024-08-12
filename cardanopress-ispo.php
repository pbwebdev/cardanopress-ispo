<?php

/**
 * Plugin Name: CardanoPress - ISPO
 * Plugin URI:  https://github.com/CardanoPress/plugin-ispo
 * Author:      CardanoPress
 * Author URI:  https://cardanopress.io
 * Description: A CardanoPress extension for ISPO
 * Version:     1.8.0
 * License:     GPL-2.0-only
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: cardanopress-ispo
 *
 * Requires at least: 5.9
 * Requires PHP:      7.4
 *
 * Requires Plugins: cardanopress
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
require_once plugin_dir_path(CP_ISPO_FILE) . 'dependencies/vendor/autoload_packages.php';

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
