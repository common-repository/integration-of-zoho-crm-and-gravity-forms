<?php

namespace BitCode\BITGFZC\Core\Util;

use BitCode\BITGFZC\Core\Database\DB;

/**
 * Class handling plugin activation.
 *
 * @since 1.0.0
 */
final class Activation
{
    public function activate()
    {
        add_action('bitgfzc_activation', array($this, 'install'));
    }

    public function install()
    {
        $installed = get_option('bitgfzc_installed');
        if ($installed) {
            $oldversion = get_option('bitgfzc_version');
        }
        if (!get_option('bitgfzc_erase_all')) {
            update_option('bitgfzc_erase_all', false);
        }
    
        if (!$installed || version_compare($oldversion, BITGFZC_VERSION, '!=')) {
            DB::migrate();
            update_option('bitgfzc_installed', time());
        }
        update_option('bitgfzc_version', BITGFZC_VERSION);
    }
}
