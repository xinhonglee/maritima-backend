<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Dental_Deactivator')) :

    /**
     * Class Dental_Deactivator
     */
    class Dental_Deactivator
    {
        static function deactivate()
        {
            global $wpdb;

            $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'dental\_%';");
        }
    }

endif; // End if class_exists check