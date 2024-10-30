<?php
namespace BitCode\BITGFZC\Core\Util;

/**
 * Class handling plugin uninstallation.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Uninstallation
{
    /**
     * Registers functionality through WordPress hooks.
     *
     * @since 1.0.0-alpha
     */
    public function register()
    {
        add_action('bitgfzc_uninstall', array($this, 'uninstall'));
    }

    public function uninstall()
    {
        if (get_option('bitgfzc_erase_all')) {
            global $wpdb;
            $tableArray = [
             $wpdb->prefix . "bitgfzc_zoho_crm_log_details",
             $wpdb->prefix . "bitgfzc_integration",
             $wpdb->prefix . "bitgfzc_gclid",
            ];
            foreach ($tableArray as $tablename) {
                $wpdb->query("DROP TABLE IF EXISTS $tablename");
            }
            $columns = ["bitgfzc_db_version", "bitgfzc_installed", "bitgfzc_version", "bitgfzc_erase_all"];
            foreach ($columns as $column) {
                delete_option($column);
            }
        }
    }
}
