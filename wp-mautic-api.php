<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           MauticAPI
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress MauticAPI
 * Description:       Connect Mautic API
 * Version:           1.0.0
 * Author:            Grégory COLLIN
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mautic-api
 * Domain Path:       /languages
 */
require_once __DIR__."/vendor/autoload.php";

// If this file is called directly, abort.
use D4rk0s\WpMauticApi\WpMauticApi;

if ( ! defined('WPINC' ) ) {
	die;
}

const WP_MAUTIC_API_VERSION = '1.0.0';


WpMauticApi::init();
register_deactivation_hook(__FILE__, [WpMauticApi::class, 'plugin_deactivation']);
