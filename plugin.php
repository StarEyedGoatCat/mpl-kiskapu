<?php

/**
 * Plugin Name: MPL Kiskapu
 * Description: A MPL-es szabályzás kiskapujául szolgáló egyszerű bővítmény.
 * Version: 1.0
 * Author: OnlineOn
 * Author URI: https://onlineon.hu
 */

final class MPL_Kiskapu_Final
{
    /**
     * Define Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Construct Function
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init_plugin']);

        $this->plugin_constants();

        // activate plugin
        register_activation_hook(__FILE__, [$this, 'activate']);
        // deactivate plugin
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // add javascript to frontend
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Init Plugin
     * @since 1.0.0
     */
    public function init_plugin()
    {
        require_once MPL_KK_PLUGIN_PATH . 'includes/class-admin.php';
        new MPL_SD_Admin();
        require_once MPL_KK_PLUGIN_PATH . 'includes/class-mpl-shipping-methode.php';
        new MPL_Shipping_Method();
    }

    /**
     * Plugin Activation
     * @since 1.0.0
     */
    public function activate()
    {
    }

    /**
     * Plugin Deactivation
     * @since 1.0.0
     */
    public function deactivate()
    {
    }

    /**
     * Plugin Constants
     * @since 1.0.0
     */
    public function plugin_constants()
    {
        define('MPL_KK_VERSION', self::VERSION);
        define('MPL_KK_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
        define('MPL_KK_PLUGIN_URL', trailingslashit(plugins_url('', __FILE__)));
    }

    /**
     * Singletone Instance
     * @since 1.0.0
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Enqueue Scripts
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('mpl-kiskapu-script', MPL_KK_PLUGIN_URL . 'assets/js/mpl-kiskapu.js', array('jquery'), '1.0.0', true);
    }
}

MPL_Kiskapu_Final::init();
