<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Dental_Scripts')) :

    class Dental_Scripts
    {
        private $version;
        private $prefix;
        private $base_url;

        public function __construct()
        {
            $this->version = DENTAL_VERSION;
            $this->base_url = DENTAL_PLUGIN_URL;
            $this->prefix = DENTAL_PLUGIN_NAME;

            add_action('wp_footer', array($this, 'load_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'load_styles'));
        }

        public function load_scripts()
        {

        }

        public function load_styles()
        {

        }

    }
endif; // End if class_exists check