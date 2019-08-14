<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Dental_Activator')) :

    class Dental_Activator {

        static function activate()
        {
            if (!get_option('dental_is_installed')) {
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                update_option('dental_is_installed', '1');
            }
        }
    }

endif; // End if class_exists check