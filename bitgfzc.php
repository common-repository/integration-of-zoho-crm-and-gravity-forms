<?php

/**
 * Plugin Name: Integration of Zoho CRM and Gravity Forms
 * Plugin URI:  https://formsintegrations.com/gravity-forms-integration-with-zoho-crm
 * Description: Sends Gravity Forms entries to Zoho CRM
 * Version:     1.0.3
 * Author:      Forms Integrations
 * Author URI:  https://formsintegrations.com
 * Text Domain: bitgfzc
 * Requires PHP: 5.6
 * Domain Path: /languages
 * License: GPLv2 or later
 */

/***
 * If try to direct access  plugin folder it will Exit
 **/
if (!defined('ABSPATH')) {
    exit;
}
global $bitgfzc_db_version;
$bitgfzc_db_version = '1.0';


// Define most essential constants.
define('BITGFZC_VERSION', '1.0.3');
define('BITGFZC_PLUGIN_MAIN_FILE', __FILE__);


require_once plugin_dir_path(__FILE__) . 'includes/loader.php';

function bitgfzc_activate_plugin()
{
    if (version_compare(PHP_VERSION, '5.6.0', '<')) {
        wp_die(
            esc_html__('bitgfzc requires PHP version 5.6.', 'bitgfzc'),
            esc_html__('Error Activating', 'bitgfzc')
        );
    }
    do_action('bitgfzc_activation');
}

register_activation_hook(__FILE__, 'bitgfzc_activate_plugin');

function bitgfzc_uninstall_plugin()
{
    do_action('bitgfzc_uninstall');
}
register_uninstall_hook(__FILE__, 'bitgfzc_uninstall_plugin');
