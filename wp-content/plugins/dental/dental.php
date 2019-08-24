<?php
/**
 * Plugin Name: Dental
 * Plugin URI: https://dental.com
 * Description: Dental
 * Version: 1.0
 * Author: XinHong Lee
 */

if (!defined('ABSPATH')) {
    exit;
}

function dental_install()
{
    require_once plugin_dir_path(__FILE__) . 'include/class_dental_activator.php';
    Dental_Activator::activate();
}

function dental_uninstall()
{
    require_once plugin_dir_path(__FILE__) . 'include/class_dental_deactivator.php';
    Dental_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'dental_install');
register_deactivation_hook(__FILE__, 'dental_uninstall');

if (!class_exists('Dental')) :

    /**
     * Main Dental Class
     * @since 1.0
     */

    final class Dental
    {
        /**
         * Dental instance.
         * @access private
         * @since  1.0
         * @var    Dental The one true Dental
         */
        private static $instance;

        /**
         * The version number of Dental.
         * @access private
         * @since  1.0
         * @var    string
         */
        private $version = '1.0.9';

        /**
         * The plugin name of Dental.
         * @access private
         * @since  1.0
         * @var    string
         */
        private $plugin_name = 'dental';

        // Instances
        private $scripts;
        private $hooks;
        private $restapi;
        private $cpt;
        private $acf;

        /**
         * Main Dental Instance
         * @since 1.0
         * @return Dental
         */

        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Dental)) {

                self::$instance = new Dental;

                if (version_compare(PHP_VERSION, '5.3', '<')) {

                    add_action('admin_notices', array('Dental', 'below_php_version_notice'));

                    return self::$instance;
                }

                self::$instance->setup_constants();
                self::$instance->includes();

                add_action('plugins_loaded', array(self::$instance, 'setup_objects'));
            }

            return self::$instance;
        }

        /**
         * Show a warning to sites running PHP < 5.3
         * @static
         * @access private
         * @since 1.0
         * @return void
         */
        public static function below_php_version_notice()
        {
            echo '<div class="error"><p>' . __(
                    'Your version of PHP is below the minimum version of PHP required by Dental. Please contact your host and request that your version be upgraded to 5.3 or later.',
                    'Dental'
                ) . '</p></div>';
        }

        /**
         * Setup plugin constants
         * @access private
         * @since 1.0
         * @return void
         */
        private function setup_constants()
        {
            // Plugin version
            if (!defined('DENTAL_VERSION')) {
                define('DENTAL_VERSION', $this->version);
            }

            // Plugin version
            if (!defined('DENTAL_PLUGIN_NAME')) {
                define('DENTAL_PLUGIN_NAME', $this->plugin_name);
            }

            // Plugin Folder Path
            if (!defined('DENTAL_PLUGIN_DIR')) {
                define('DENTAL_PLUGIN_DIR', plugin_dir_path(__FILE__));
            }

            // Plugin Folder URL
            if (!defined('DENTAL_PLUGIN_URL')) {
                define('DENTAL_PLUGIN_URL', plugin_dir_url(__FILE__));
            }

            // Plugin Root File
            if (!defined('DENTAL_PLUGIN_FILE')) {
                define('DENTAL_PLUGIN_FILE', __FILE__);
            }

            // RESTful None Name
            if (!defined('DENTAL_NONCE_NAME')) {
                define('DENTAL_NONCE_NAME', 'wp_rest');
            }
        }

        /**
         * Include required files
         * @access private
         * @since 1.0
         * @return void
         */
        private function includes()
        {
            // Multilingual
            require_once DENTAL_PLUGIN_DIR . 'include/class_dental_il8n.php';

            // Enqueue scripts
            require_once DENTAL_PLUGIN_DIR . 'include/class_dental_scripts.php';

            // Custom Post Types
            require_once DENTAL_PLUGIN_DIR . 'include/class_dental_cpt.php';

            // Advanced Custom Fields
            require_once DENTAL_PLUGIN_DIR . 'include/class_dental_acf.php';

            // RESTful API
            require_once DENTAL_PLUGIN_DIR . 'Hook/class_dental_hook.php';

            // RESTful API
            require_once DENTAL_PLUGIN_DIR . 'REST/class_dental_restapi.php';

            if (is_admin()) {
                require_once DENTAL_PLUGIN_DIR . 'admin/class_dental_admin.php';
            }
        }

        /**
         * Setup all objects
         * @access public
         * @since 1.0.0
         * @return void
         */
        public function setup_objects()
        {
           self::$instance->scripts = new Dental_Scripts();
           self::$instance->cpt = new Dental_CPT();
           self::$instance->hooks = new Dental_Hook();
           self::$instance->restapi = new Dental_RESTApi();
           self::$instance->acf = new Dental_ACF();
        }

    }

endif; // End if class_exists check

/**
 * Dental Instance
 * @since 1.0
 * @return Dental The one true Dental Instance
 */

function dental()
{
    return Dental::instance();
}

dental();

