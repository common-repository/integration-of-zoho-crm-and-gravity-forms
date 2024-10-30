<?php

namespace BitCode\BITGFZC\Admin;

use BitCode\BITGFZC\Core\Util\DateTimeHelper;
use BitCode\BITGFZC\Admin\Gclid\Handler as GclidHandler;

/**
 * The admin menu and page handler class
 */

class Admin_Bar
{
    public function register()
    {
        add_action('in_admin_header', [$this, 'RemoveAdminNotices']);
        add_action('admin_menu', [ $this, 'AdminMenu' ], 9, 0);
        add_action('admin_enqueue_scripts', [ $this, 'AdminAssets' ]);
        add_filter('script_loader_tag', [$this, 'filterScriptTag'], 0, 3);

    }


    /**
     * Register the admin menu
     *
     * @return void
     */
    public function AdminMenu()
    {
        global $submenu;
        $capability = apply_filters('bitgfzc_form_access_capability', 'manage_options');
        if (current_user_can($capability)) {

            add_menu_page(__('Zoho CRM integration for Gravity Forms', 'bitgfzc'), 'Gravity Forms Zoho CRM', $capability, 'bitgfzc', array($this, 'RootPage'), 'data:image/svg+xml;base64,' . base64_encode('<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><defs><style>.cls-1{fill:#ee5b2d;}.cls-2{fill:#397cbc;}</style></defs><path class="cls-1" d="M239,47.1c-3.6,4.6-6.56,8.46-9.6,12.26-7.26,9.07-14.62,18.07-21.78,27.21a6.7,6.7,0,0,1-6,2.79c-26.61-.1-53.22-.06-79.82-.06-8.14,0-16.19.46-24,3.22-11.27,4-20.22,10.68-25.51,21.59-6.81,14.06-2.63,32,9.84,41.73,9.81,7.67,21.11,11,33.28,11.27,14.49.33,29,.09,43.49.1,1,0,1.92.06,2.87,0,1.82-.14,2.6.58,2.53,2.47-.11,2.54,0,5.09,0,7.64q0,14.1,0,28.2c0,3.44,0,3.51-3.28,3.51-15,0-29.95.2-44.92-.09-20.17-.38-39.42-4.73-57.09-14.83-18.83-10.76-32.75-25.85-38.75-47-7.06-24.84-2.4-47.6,14.55-67.39C47.28,65.06,63.67,56.66,82,51.64A133.72,133.72,0,0,1,116.9,47c39.51-.14,79-.07,118.53-.08C236.21,46.93,237,47,239,47.1Z"/><path class="cls-2" d="M95.3,143.93c2-2.49,3.55-4.45,5.17-6.33,8-9.27,16.12-18.46,24-27.81,1.59-1.88,3.33-2,5.45-2q36.08,0,72.13,0c9.55,0,19.1,0,28.65,0,3.33,0,3.39.08,3.39,3.48q0,47,0,94.09c0,3.58,0,3.62-3.67,3.63q-21.74,0-43.46,0c-4.48,0-4.48.55-4.47-4.3q0-27.7,0-55.41c0-1.11,0-2.23,0-3.34.08-1.46-.66-2-2-2-1.11,0-2.23,0-3.34,0H95.3Z"/></svg>'), 30);
            $submenu['bitgfzc'][] = array(__('Forms', 'bitgfzc'), $capability, 'admin.php?page=bitgfzc#/');
        }
    }
    /**
     * Load the asset libraries
     *
     * @return void
     */
    public function AdminAssets($current_screen)
    {
        if (strpos($current_screen, 'bitgfzc') === false) {
            return;
        }
        $parsed_url = parse_url(get_admin_url());
        $site_url = $parsed_url['scheme'] . "://" . $parsed_url['host'];
        $site_url .= empty($parsed_url['port']) ? null : ':' . $parsed_url['port'];
        $base_path_admin =  str_replace($site_url, '', get_admin_url());
        $prefix = 'FITGFZC';
        if (is_readable(BITGFZC_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'port')) {
            $devPort = file_get_contents(BITGFZC_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'port');
            $devUrl = 'http://localhost:' . $devPort;
            wp_enqueue_script(
                'vite-client-helper-' . $prefix . '-MODULE',
                $devUrl . '/config/devHotModule.js',
                [],
                null
            );

            wp_enqueue_script(
                'vite-client-' . $prefix . '-MODULE',
                $devUrl . '/@vite/client',
                [],
                null
            );
            wp_enqueue_script(
                'index-' . $prefix . '-MODULE',
                $devUrl . '/index.jsx',
                [],
                null
            );
        } else {
            wp_enqueue_script(
                'index-' . $prefix . '-MODULE',
                BITGFZC_ASSET_URI . "/index-" . BITGFZC_VERSION . ".js",
                [],
                null
            );
        }
        $gclidHandler = new GclidHandler();
        $gclid_enabled = $gclidHandler->get_enabled_form_lsit();
        $all_forms = [];
        if (class_exists('GFFormsModel') && is_callable('GFFormsModel::get_forms')) {
            $forms = \GFFormsModel::get_forms(1);//param is_active = 1
            if ($forms) {
                foreach ($forms as $form) {
                    $all_forms[] = (object)[
                        'id' => $form->id,
                        'title' => $form->title,
                        'gclid' => in_array($form->id, $gclid_enabled)
                    ];
                }
            }
        }
        $bitgfzc = apply_filters(
            'bitgfzc_localized_script',
            array(
                'nonce'     => wp_create_nonce('bitgfzc_nonce'),
                'assetsURL' => BITGFZC_ASSET_URI,
                'baseURL'   => $base_path_admin . 'admin.php?page=bitgfzc#',
                'ajaxURL'   => admin_url('admin-ajax.php'),
                'allForms'  => is_wp_error($all_forms) ? null : $all_forms,
                'erase_all'  => get_option('bitgfzc_erase_all'),
                'dateFormat'  => get_option('date_format'),
                'timeFormat'  => get_option('time_format'),
                'new_page'  => admin_url('admin.php?page=gf_new_form'),
                'timeZone'  => DateTimeHelper::wp_timezone_string(),
                'redirect' => get_rest_url() . 'bitgfzc/redirect',
            )
        );
        if (get_locale() !== 'en_US' && file_exists(BITGFZC_PLUGIN_DIR_PATH . '/languages/generatedString.php')) {
            include_once BITGFZC_PLUGIN_DIR_PATH . '/languages/generatedString.php';
            $bitgfzc['translations'] = $i18nStrings;
        }
        wp_localize_script('index-' . $prefix . '-MODULE', 'bitgfzc', $bitgfzc);
    }

    /**
     * apps-root id provider
     * @return void
     */
    public function RootPage()
    {
        require_once BITGFZC_PLUGIN_DIR_PATH . '/views/view-root.php';
    }

    public function filterScriptTag($html, $handle, $href)
    {
        $newTag = $html;
        $prefix = 'FITGFZC';
        if (preg_match('/' . $prefix . '-MODULE/', $handle)) {
            $newTag = preg_replace('/<script /', '<script type="module" ', $newTag);
        }
        return $newTag;
    }

    public function RemoveAdminNotices()
    {
        global $plugin_page;
        if (strpos($plugin_page, 'bitgfzc') === false) {
            return;
        }
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}
