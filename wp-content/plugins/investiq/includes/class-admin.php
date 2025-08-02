<?php
/**
 * InvestIQ Admin Class
 * 
 * Handles all admin functionality including:
 * - Settings pages
 * - Data management
 * - AJAX operations
 * 
 * @package InvestIQ
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class InvestIQ_Admin {
    private static $instance = null;
    private $database;
    
    /**
     * Get singleton instance
     * 
     * @return InvestIQ_Admin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->database = InvestIQ_Database::get_instance();
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Admin menu
        add_action('admin_menu', [$this, 'register_admin_menu']);
        
        // Settings
        add_action('admin_init', [$this, 'register_settings']);
        
        // Assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // AJAX handlers
        add_action('wp_ajax_investiq_save_city', [$this, 'ajax_save_city']);
        add_action('wp_ajax_investiq_delete_city', [$this, 'ajax_delete_city']);
        add_action('wp_ajax_investiq_save_sector', [$this, 'ajax_save_sector']);
        add_action('wp_ajax_investiq_delete_sector', [$this, 'ajax_delete_sector']);
        add_action('wp_ajax_investiq_save_opportunity', [$this, 'ajax_save_opportunity']);
        add_action('wp_ajax_investiq_delete_opportunity', [$this, 'ajax_delete_opportunity']);
    }
    
    /**
     * Register admin menu items
     */
    public function register_admin_menu() {
        $menu_icon = 'dashicons-chart-line';
        
        // Main menu
        add_menu_page(
            __('InvestIQ Dashboard', 'investiq'),
            __('InvestIQ', 'investiq'),
            'manage_options',
            'investiq',
            [$this, 'render_dashboard_page'],
            $menu_icon,
            80
        );
        
        // Submenus
        add_submenu_page(
            'investiq',
            __('Dashboard', 'investiq'),
            __('Dashboard', 'investiq'),
            'manage_options',
            'investiq',
            [$this, 'render_dashboard_page']
        );
        
        add_submenu_page(
            'investiq',
            __('Cities Management', 'investiq'),
            __('Cities', 'investiq'),
            'manage_options',
            'investiq-cities',
            [$this, 'render_cities_page']
        );
        
        add_submenu_page(
            'investiq',
            __('Sectors Management', 'investiq'),
            __('Sectors', 'investiq'),
            'manage_options',
            'investiq-sectors',
            [$this, 'render_sectors_page']
        );
        
        add_submenu_page(
            'investiq',
            __('Opportunities Management', 'investiq'),
            __('Opportunities', 'investiq'),
            'manage_options',
            'investiq-opportunities',
            [$this, 'render_opportunities_page']
        );
        
        add_submenu_page(
            'investiq',
            __('Market Indicators', 'investiq'),
            __('Indicators', 'investiq'),
            'manage_options',
            'investiq-indicators',
            [$this, 'render_indicators_page']
        );
        
        add_submenu_page(
            'investiq',
            __('Settings', 'investiq'),
            __('Settings', 'investiq'),
            'manage_options',
            'investiq-settings',
            [$this, 'render_settings_page']
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        // General settings section
        add_settings_section(
            'investiq_general',
            __('General Settings', 'investiq'),
            [$this, 'render_general_section'],
            'investiq-settings'
        );
        
        // AI Recommendations
        add_settings_field(
            'investiq_enable_ai',
            __('AI Recommendations', 'investiq'),
            [$this, 'render_ai_field'],
            'investiq-settings',
            'investiq_general'
        );
        
        // Indicators section
        add_settings_section(
            'investiq_indicators',
            __('Indicator Settings', 'investiq'),
            [$this, 'render_indicators_section'],
            'investiq-settings'
        );
        
        // Demand threshold
        add_settings_field(
            'investiq_demand_threshold',
            __('High Demand Threshold', 'investiq'),
            [$this, 'render_demand_threshold_field'],
            'investiq-settings',
            'investiq_indicators'
        );
        
        // Saturation threshold
        add_settings_field(
            'investiq_saturation_threshold',
            __('High Saturation Threshold', 'investiq'),
            [$this, 'render_saturation_threshold_field'],
            'investiq-settings',
            'investiq_indicators'
        );
        
        // Register the setting
        register_setting('investiq_settings', 'investiq_options', [
            'sanitize_callback' => [$this, 'sanitize_settings']
        ]);
    }
    
    /**
     * Sanitize settings before saving
     */
    public function sanitize_settings($input) {
        $output = [];
        
        // AI Recommendations
        $output['enable_ai'] = isset($input['enable_ai']) ? 1 : 0;
        
        // Thresholds
        $output['demand_threshold'] = isset($input['demand_threshold']) 
            ? max(0, min(100, intval($input['demand_threshold']))) 
            : 70;
        
        $output['saturation_threshold'] = isset($input['saturation_threshold']) 
            ? max(0, min(100, intval($input['saturation_threshold']))) 
            : 30;

            
        return $output;
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'investiq') === false) {
            return;
        }
        
        // CSS
        wp_enqueue_style(
            'investiq-admin-css',
            INVESTIQ_PLUGIN_URL . 'assets/css/admin.css',
            [],
            INVESTIQ_VERSION
        );
        
        // JS
        wp_enqueue_script(
            'investiq-admin-js',
            INVESTIQ_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery', 'wp-util'],
            INVESTIQ_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'investiq-admin-js',
            'investiqAdmin',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('investiq_admin_nonce'),
                'i18n' => [
                    'confirm_delete' => __('Are you sure you want to delete this item?', 'investiq'),
                    'error' => __('An error occurred. Please try again.', 'investiq')
                ]
            ]
        );
    }
    
    /**
     * Render dashboard page
     */
    public function render_dashboard_page() {
        $stats = $this->get_dashboard_stats();
        
        include INVESTIQ_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }
    
    /**
     * Render cities management page
     */
    public function render_cities_page() {
        $cities = $this->database->get_cities();
        
        include INVESTIQ_PLUGIN_DIR . 'templates/admin/cities.php';
    }
    
    /**
     * Render sectors management page
     */
    public function render_sectors_page() {
        $sectors = $this->database->get_sectors();
        
        include INVESTIQ_PLUGIN_DIR . 'templates/admin/sectors.php';
    }
    
    /**
     * Render opportunities management page
     */
    public function render_opportunities_page() {
        $opportunities = $this->database->get_opportunities(['limit' => 50]);
        $cities = $this->database->get_cities(['is_active' => 1]);
        $sectors = $this->database->get_sectors(['is_active' => 1]);
        
        include INVESTIQ_PLUGIN_DIR . 'templates/admin/opportunities.php';
    }
    
    /**
     * Render indicators page
     */
    public function render_indicators_page() {
        include INVESTIQ_PLUGIN_DIR . 'templates/admin/indicators.php';
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        include INVESTIQ_PLUGIN_DIR . 'templates/admin/settings.php';
    }
    
    /**
     * Render general settings section
     */
    public function render_general_section() {
        echo '<p>' . __('General platform settings and configurations.', 'investiq') . '</p>';
    }
    
    /**
     * Render AI recommendations field
     */
    public function render_ai_field() {
        $options = get_option('investiq_options');
        $enabled = isset($options['enable_ai']) ? $options['enable_ai'] : 1;
        ?>
        <label>
            <input type="checkbox" name="investiq_options[enable_ai]" value="1" <?php checked($enabled, 1); ?>>
            <?php _e('Enable AI Recommendations', 'investiq'); ?>
        </label>
        <p class="description">
            <?php _e('When enabled, the system will provide personalized recommendations based on user preferences and behavior.', 'investiq'); ?>
        </p>
        <?php
    }
    
    /**
     * Render indicators section
     */
    public function render_indicators_section() {
        echo '<p>' . __('Configure market indicator thresholds and warning levels.', 'investiq') . '</p>';
    }
    
    /**
     * Render demand threshold field
     */
    public function render_demand_threshold_field() {
        $options = get_option('investiq_options');
        $threshold = isset($options['demand_threshold']) ? $options['demand_threshold'] : 70;
        ?>
        <input type="number" name="investiq_options[demand_threshold]" 
               value="<?php echo esc_attr($threshold); ?>" min="0" max="100">
        <p class="description">
            <?php _e('Minimum percentage to consider demand for opportunities as high.', 'investiq'); ?>
        </p>
        <?php
    }
    
    /**
     * Render saturation threshold field
     */
    public function render_saturation_threshold_field() {
        $options = get_option('investiq_options');
        $threshold = isset($options['saturation_threshold']) ? $options['saturation_threshold'] : 30;
        ?>
        <input type="number" name="investiq_options[saturation_threshold]" 
               value="<?php echo esc_attr($threshold); ?>" min="0" max="100">
        <p class="description">
            <?php _e('Minimum percentage to consider a sector or city as saturated.', 'investiq'); ?>
        </p>
        <?php
    }
    
    /**
     * Get dashboard statistics
     */
    private function get_dashboard_stats() {
        return [
            'total_cities' => $this->database->count_cities(),
            'active_cities' => $this->database->count_cities(['is_active' => 1]),
            'total_sectors' => $this->database->count_sectors(),
            'active_sectors' => $this->database->count_sectors(['is_active' => 1]),
            'total_opportunities' => $this->database->count_opportunities(),
            'active_opportunities' => $this->database->count_opportunities(['is_active' => 1]),
            'recent_opportunities' => $this->database->get_opportunities(['limit' => 5])
        ];
    }
    
    /**
     * AJAX: Save city data
     */
    public function ajax_save_city() {
        try {
            $this->verify_ajax_request();
            
            $data = [
                'city_name' => sanitize_text_field($_POST['city_name'] ?? ''),
                'latitude' => floatval($_POST['latitude'] ?? 0),
                'longitude' => floatval($_POST['longitude'] ?? 0),
                'opportunities_count' => intval($_POST['opportunities_count'] ?? 0),
                'demand_level' => $this->validate_demand_level($_POST['demand_level'] ?? 'medium'),
                'avg_return' => floatval($_POST['avg_return'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if (empty($data['city_name'])) {
                throw new Exception(__('City name is required', 'investiq'));
            }
            
            // Handle update or insert
            if (!empty($_POST['city_id'])) {
                $city_id = absint($_POST['city_id']);
                $result = $this->database->update_city($city_id, $data);
                $message = __('City updated successfully', 'investiq');
            } else {
                $result = $this->database->insert_city($data);
                $message = __('City added successfully', 'investiq');
            }
            
            if (!$result) {
                throw new Exception(__('Failed to save city', 'investiq'));
            }
            
            wp_send_json_success([
                'message' => $message,
                'city_id' => $result
            ]);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * AJAX: Delete city
     */
    public function ajax_delete_city() {
        try {
            $this->verify_ajax_request();
            
            $city_id = absint($_POST['city_id'] ?? 0);
            if (!$city_id) {
                throw new Exception(__('Invalid city ID', 'investiq'));
            }
            
            $result = $this->database->delete_city($city_id);
            if (!$result) {
                throw new Exception(__('Failed to delete city', 'investiq'));
            }
            
            wp_send_json_success([
                'message' => __('City deleted successfully', 'investiq')
            ]);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * AJAX: Save sector data
     */
    public function ajax_save_sector() {
        try {
            $this->verify_ajax_request();
            
            $data = [
                'sector_name' => sanitize_text_field($_POST['sector_name'] ?? ''),
                'sector_icon' => sanitize_text_field($_POST['sector_icon'] ?? ''),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if (empty($data['sector_name'])) {
                throw new Exception(__('Sector name is required', 'investiq'));
            }
            
            // Handle update or insert
            if (!empty($_POST['sector_id'])) {
                $sector_id = absint($_POST['sector_id']);
                $result = $this->database->update_sector($sector_id, $data);
                $message = __('Sector updated successfully', 'investiq');
            } else {
                $result = $this->database->insert_sector($data);
                $message = __('Sector added successfully', 'investiq');
            }
            
            if (!$result) {
                throw new Exception(__('Failed to save sector', 'investiq'));
            }
            
            wp_send_json_success([
                'message' => $message,
                'sector_id' => $result
            ]);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * AJAX: Delete sector
     */
    public function ajax_delete_sector() {
        try {
            $this->verify_ajax_request();
            
            $sector_id = absint($_POST['sector_id'] ?? 0);
            if (!$sector_id) {
                throw new Exception(__('Invalid sector ID', 'investiq'));
            }
            
            $result = $this->database->delete_sector($sector_id);
            if (!$result) {
                throw new Exception(__('Failed to delete sector', 'investiq'));
            }
            
            wp_send_json_success([
                'message' => __('Sector deleted successfully', 'investiq')
            ]);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * AJAX: Save opportunity data
     */
    public function ajax_save_opportunity() {
        try {
            $this->verify_ajax_request();
            
            $data = [
                'title' => sanitize_text_field($_POST['title'] ?? ''),
                'description' => sanitize_textarea_field($_POST['description'] ?? ''),
                'city_id' => absint($_POST['city_id'] ?? 0),
                'sector_id' => absint($_POST['sector_id'] ?? 0),
                'investment_amount' => floatval($_POST['investment_amount'] ?? 0),
                'expected_return' => floatval($_POST['expected_return'] ?? 0),
                'risk_level' => $this->validate_risk_level($_POST['risk_level'] ?? 'medium'),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Validate required fields
            if (empty($data['title'])) {
                throw new Exception(__('Title is required', 'investiq'));
            }
            
            if (empty($data['city_id'])) {
                throw new Exception(__('City is required', 'investiq'));
            }
            
            if (empty($data['sector_id'])) {
                throw new Exception(__('Sector is required', 'investiq'));
            }
            
            // Handle update or insert
            if (!empty($_POST['opportunity_id'])) {
                $opportunity_id = absint($_POST['opportunity_id']);
                $result = $this->database->update_opportunity($opportunity_id, $data);
                $message = __('Opportunity updated successfully', 'investiq');
            } else {
                $result = $this->database->insert_opportunity($data);
                $message = __('Opportunity added successfully', 'investiq');
            }
            
            if (!$result) {
                throw new Exception(__('Failed to save opportunity', 'investiq'));
            }
            
            wp_send_json_success([
                'message' => $message,
                'opportunity_id' => $result
            ]);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * AJAX: Delete opportunity
     */
    public function ajax_delete_opportunity() {
        try {
            $this->verify_ajax_request();
            
            $opportunity_id = absint($_POST['opportunity_id'] ?? 0);
            if (!$opportunity_id) {
                throw new Exception(__('Invalid opportunity ID', 'investiq'));
            }
            
            $result = $this->database->delete_opportunity($opportunity_id);
            if (!$result) {
                throw new Exception(__('Failed to delete opportunity', 'investiq'));
            }
            
            wp_send_json_success([
                'message' => __('Opportunity deleted successfully', 'investiq')
            ]);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Verify AJAX request
     */
    private function verify_ajax_request() {
        check_ajax_referer('investiq_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            throw new Exception(__('You do not have permission to perform this action', 'investiq'));
        }
    }
    
    /**
     * Validate demand level
     */
    private function validate_demand_level($level) {
        $valid_levels = ['high', 'medium', 'low'];
        return in_array($level, $valid_levels, true) ? $level : 'medium';
    }
    
    /**
     * Validate risk level
     */
    private function validate_risk_level($level) {
        $valid_levels = ['low', 'medium', 'high'];
        return in_array($level, $valid_levels, true) ? $level : 'medium';
    }
    
    /**
     * Get sector icon HTML
     */
    public function get_sector_icon_html($icon) {
        $icons = [
            'laptop' => '<i class="fas fa-laptop"></i>',
            'home' => '<i class="fas fa-home"></i>',
            'umbrella-beach' => '<i class="fas fa-umbrella-beach"></i>',
            'shopping-cart' => '<i class="fas fa-shopping-cart"></i>',
            'industry' => '<i class="fas fa-industry"></i>',
            'concierge-bell' => '<i class="fas fa-concierge-bell"></i>',
            'tractor' => '<i class="fas fa-tractor"></i>',
            'graduation-cap' => '<i class="fas fa-graduation-cap"></i>',
            'hospital' => '<i class="fas fa-hospital"></i>',
            'bolt' => '<i class="fas fa-bolt"></i>'
        ];
        
        return $icons[$icon] ?? '<i class="fas fa-chart-pie"></i>';
    }
}