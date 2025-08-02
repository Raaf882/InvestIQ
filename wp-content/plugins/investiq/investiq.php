<?php
/*
Plugin Name: InvestIQ - منصة الاستثمار الذكية
Plugin URI: https://investiq.example.com
Description: منصة ذكية لاستكشاف فرص الاستثمار في منطقة عسير
Version: 2.0.1
Author: InvestIQ Team
Author URI: https://investiq.example.com
Text Domain: investiq
Domain Path: /languages
License: GPL-2.0+
*/

defined('ABSPATH') || exit;

// Define plugin constants
define('INVESTIQ_VERSION', '2.0.1');
define('INVESTIQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('INVESTIQ_PLUGIN_URL', plugin_dir_url(__FILE__));
define('INVESTIQ_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'InvestIQ_';
    $base_dir = INVESTIQ_PLUGIN_DIR . 'includes/';
    
    // Does the class use the namespace prefix?
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    
    $relative_class = substr($class, strlen($prefix));
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize the plugin
add_action('plugins_loaded', 'investiq_init_plugin');

function investiq_init_plugin() {
    // Verify all required classes exist
    $required_classes = [
        'InvestIQ_Database',
        'InvestIQ_Dashboard',
        'InvestIQ_Onboarding',
        'InvestIQ_Admin'
    ];
    
    $missing_classes = [];
    
    foreach ($required_classes as $class) {
        if (!class_exists($class)) {
            $missing_classes[] = $class;
        }
    }
    
    if (!empty($missing_classes)) {
        add_action('admin_notices', function() use ($missing_classes) {
            $class_list = implode(', ', $missing_classes);
            ?>
            <div class="notice notice-error">
                <p>
                    <?php 
                    printf(
                        __('InvestIQ Error: Required classes (%s) not found. Please reinstall the plugin.', 'investiq'),
                        '<strong>' . esc_html($class_list) . '</strong>'
                    );
                    ?>
                </p>
            </div>
            <?php
        });
        return;
    }
    
    // Initialize the plugin
    InvestIQ_Plugin::get_instance();
}

/**
 * Main plugin class
 */
final class InvestIQ_Plugin {
    private static $instance;
    private $database;
    private $dashboard;
    private $onboarding;
    private $admin;
    
    /**
     * Singleton pattern
     */
    public static function get_instance() {
        if (!isset(self::$instance) && !(self::$instance instanceof self)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }
    
    /**
     * Initialize the plugin
     */
    private function init() {
        $this->load_dependencies();
        $this->register_hooks();
    }
    
    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        $this->database = new InvestIQ_Database();
        $this->dashboard = new InvestIQ_Dashboard($this->database);
        $this->onboarding = new InvestIQ_Onboarding($this->database);
        $this->admin = new InvestIQ_Admin($this->database);
    }
    
    /**
     * Register all hooks
     */
    private function register_hooks() {
        // Activation/Deactivation hooks
        register_activation_hook(__FILE__, [$this->database, 'activate']);
        register_deactivation_hook(__FILE__, [$this->database, 'deactivate']);
        register_uninstall_hook(__FILE__, ['InvestIQ_Database', 'uninstall']);
        
        // Localization
        add_action('init', [$this, 'load_textdomain']);
        
        // Frontend assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'investiq',
            false,
            dirname(INVESTIQ_PLUGIN_BASENAME) . '/languages/'
        );
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        // Google Fonts (Arabic + English)
        wp_enqueue_style(
            'investiq-fonts',
            'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Poppins:wght@400;500;600&display=swap',
            [],
            null
        );
        
        // Main CSS
        wp_enqueue_style(
            'investiq-main',
            INVESTIQ_PLUGIN_URL . 'assets/css/main.css',
            [],
            INVESTIQ_VERSION
        );
        
        // Main JS
        wp_enqueue_script(
            'investiq-main',
            INVESTIQ_PLUGIN_URL . 'assets/js/main.js',
            ['jquery'],
            INVESTIQ_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('investiq-main', 'investiq_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('investiq_ajax_nonce'),
            'current_user_id' => get_current_user_id(),
            'is_admin' => current_user_can('manage_options'),
            'strings' => [
                'loading' => esc_html__('جاري التحميل...', 'investiq'),
                'error' => esc_html__('حدث خطأ، يرجى المحاولة لاحقاً', 'investiq')
            ]
        ]);
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'investiq') === false) {
            return;
        }
        
        wp_enqueue_style(
            'investiq-admin',
            INVESTIQ_PLUGIN_URL . 'assets/css/admin.css',
            [],
            INVESTIQ_VERSION
        );
        
        wp_enqueue_script(
            'investiq-admin',
            INVESTIQ_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery', 'wp-util'],
            INVESTIQ_VERSION,
            true
        );
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'investiq'), '2.0.1');
    }
}