<?php
namespace BitCode\BITGFZC;

/**
 * Main class for the plugin.
 *
 * @since 1.0.0-alpha
 */
use BitCode\BITGFZC\Core\Database\DB;
use BitCode\BITGFZC\Admin\Admin_Bar;
use BitCode\BITGFZC\Admin\AdminHooks;
use BitCode\BITGFZC\Core\Util\Request;
use BitCode\BITGFZC\Core\Util\Activation;
use BitCode\BITGFZC\Core\Util\Deactivation;
use BitCode\BITGFZC\Core\Util\Uninstallation;
use BitCode\BITGFZC\Core\Ajax\AjaxService;
use BitCode\BITGFZC\Integration\Integrations;

final class Plugin
{

    /**
     * Main instance of the plugin.
     *
     * @since 1.0.0-alpha
     * @var   Plugin|null
     */
    private static $instance = null;

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function initialize()
    {
        add_action('plugins_loaded', [$this, 'init_plugin'], 11);
        (new Activation())->activate();
        (new Deactivation())->register();
        (new Uninstallation())->register();
    }
    
    public function init_plugin()
    {
        add_action('init', array($this, 'init_classes'), 10);
        add_filter('plugin_action_links_' . plugin_basename(BITGFZC_PLUGIN_MAIN_FILE), array( $this, 'plugin_action_links' ));
    }

    public function gfNotFound()
    {
        echo '<div class="notice notice-error  is-dismissible"><p>Gravity Forms  plugin is required for Zoho CRM integration.<p></div>';
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes()
    {
        if (!function_exists('gravity_form') || !is_callable('gravity_form')) {
            add_action('admin_notices', [$this ,'gfNotFound'], 11);
            return;
        }
        if (Request::Check('admin')) {
            (new Admin_Bar())->register();
        }
        if (Request::Check('ajax')) {
            new AjaxService();
        }
        (new AdminHooks())->register();
        (new Integrations())->registerHooks();
    }

    /**
     * Plugin action links
     *
     * @param  array $links
     *
     * @return array
     */
    public function plugin_action_links($links)
    {
        $links[] = '<a href="https://bitpress.pro/documentation" target="_blank">' . __('Docs', 'bitgfzc') . '</a>';

        return $links;
    }

    /**
     * Retrieves the main instance of the plugin.
     *
     * @since 1.0.0-alpha
     *
     * @return bitgfzc Plugin main instance.
     */
    public static function instance()
    {
        return static::$instance;
    }

    public static function update_tables()
    {
        if (! current_user_can('manage_options')) {
            return;
        }
        global $bitgfzc_db_version;
        $installed_db_version = get_site_option("bitgfzc_db_version");
        if ($installed_db_version!=$bitgfzc_db_version) {
            DB::migrate();
        }
    }
    /**
     * Loads the plugin main instance and initializes it.
     *
     * @since 1.0.0-alpha
     *
     * @param string $main_file Absolute path to the plugin main file.
     * @return bool True if the plugin main instance could be loaded, false otherwise./
     */
    public static function load($main_file)
    {
        if (null !== static::$instance) {
            return false;
        }
        static::$instance = new static($main_file);
        static::$instance->initialize();
        return true;
    }
}
