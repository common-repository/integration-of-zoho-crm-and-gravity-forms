<?php
if (!defined('ABSPATH')) {
    exit;
}
$scheme = parse_url(home_url())['scheme'];
define('BITGFZC_PLUGIN_BASENAME', plugin_basename(BITGFZC_PLUGIN_MAIN_FILE));
define('BITGFZC_PLUGIN_DIR_PATH', plugin_dir_path(BITGFZC_PLUGIN_MAIN_FILE));
define('BITGFZC_ROOT_URI', set_url_scheme(plugins_url('', BITGFZC_PLUGIN_MAIN_FILE), $scheme));
define('BITGFZC_ASSET_URI', BITGFZC_ROOT_URI . '/assets');
define('BITGFZC_ASSET_JS_URI', BITGFZC_ROOT_URI . '/assets/js');
// Autoload vendor files.
require_once BITGFZC_PLUGIN_DIR_PATH . 'vendor/autoload.php';
// Initialize the plugin.
BitCode\BITGFZC\Plugin::load(BITGFZC_PLUGIN_MAIN_FILE);

