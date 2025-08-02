<?php
/**
 * InvestIQ Onboarding Handler
 * 
 * Handles user onboarding process including:
 * - Preference collection
 * - Progress tracking
 * - Data validation
 * 
 * @package InvestIQ
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class InvestIQ_Onboarding {
    private static $instance = null;
    private $database;
    private $current_step = 1;
    private $total_steps = 3;

    /**
     * Get singleton instance
     * 
     * @return InvestIQ_Onboarding
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
        // Shortcode
        add_shortcode('investiq_onboarding', [$this, 'render_onboarding']);
        
        // AJAX handlers
        add_action('wp_ajax_investiq_save_onboarding', [$this, 'save_onboarding_step']);
        add_action('wp_ajax_investiq_complete_onboarding', [$this, 'complete_onboarding']);
        
        // Redirects
        add_action('template_redirect', [$this, 'handle_onboarding_redirects']);
        
        // Assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Enqueue onboarding assets
     */
    public function enqueue_assets() {
        if (is_page('onboarding')) {
            // CSS
            wp_enqueue_style(
                'investiq-onboarding-css',
                INVESTIQ_PLUGIN_URL . 'assets/css/onboarding.css',
                [],
                INVESTIQ_VERSION
            );
            
            // JS
            wp_enqueue_script(
                'investiq-onboarding-js',
                INVESTIQ_PLUGIN_URL . 'assets/js/onboarding.js',
                ['jquery', 'wp-util'],
                INVESTIQ_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script(
                'investiq-onboarding-js',
                'investiqOnboarding',
                [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('investiq_onboarding_nonce'),
                    'steps' => $this->total_steps,
                    'i18n' => [
                        'error' => __('حدث خطأ، يرجى المحاولة مرة أخرى', 'investiq'),
                        'required' => __('هذا الحقل مطلوب', 'investiq')
                    ]
                ]
            );
        }
    }

    /**
     * Handle onboarding redirects
     */
    public function handle_onboarding_redirects() {
        if (!is_user_logged_in() || is_admin()) {
            return;
        }

        // Skip for administrators
        if (current_user_can('manage_options')) {
            return;
        }

        // Check if we're already on the onboarding page
        if (is_page('onboarding')) {
            return;
        }

        // Check if user needs to complete onboarding
        if (!$this->is_onboarding_complete(get_current_user_id())) {
            $redirect_url = add_query_arg(
                'redirect_to',
                urlencode($_SERVER['REQUEST_URI']),
                get_permalink(get_page_by_path('onboarding'))
            );
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Check if user has completed onboarding
     * 
     * @param int $user_id
     * @return bool
     */
    public function is_onboarding_complete($user_id) {
        $prefs = $this->database->get_user_preferences($user_id);
        return !empty($prefs) && !empty($prefs['onboarding_complete']);
    }

    /**
     * Render onboarding process
     */
    public function render_onboarding() {
        if (!is_user_logged_in()) {
            return $this->render_login_prompt();
        }

        $user_id = get_current_user_id();
        $current_prefs = $this->database->get_user_preferences($user_id);

        // Prepare template data
        $data = [
            'steps' => $this->get_onboarding_steps(),
            'current_step' => $this->get_current_step($current_prefs),
            'cities' => $this->database->get_cities(['is_active' => 1]),
            'sectors' => $this->database->get_sectors(['is_active' => 1]),
            'risk_levels' => $this->get_risk_levels(),
            'capital_ranges' => $this->get_capital_ranges(),
            'current_prefs' => $current_prefs,
            'redirect_to' => esc_url($_GET['redirect_to'] ?? home_url())
        ];

        return $this->render_template('onboarding', $data);
    }

    /**
     * Save onboarding step via AJAX
     */
    public function save_onboarding_step() {
        try {
            $this->verify_ajax_request();

            $user_id = get_current_user_id();
            $step = absint($_POST['step']);
            $data = $this->validate_step_data($step, $_POST);

            // Save step data
            $this->database->update_user_preferences($user_id, $data);

            // Log progress
            $this->database->log_user_action(
                $user_id,
                'onboarding_progress',
                null,
                null,
                sprintf('Completed step %d/%d', $step, $this->total_steps)
            );

            wp_send_json_success([
                'message' => __('تم حفظ البيانات بنجاح', 'investiq'),
                'next_step' => $step + 1
            ]);

        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Complete onboarding process
     */
    public function complete_onboarding() {
        try {
            $this->verify_ajax_request();

            $user_id = get_current_user_id();
            $redirect_url = esc_url_raw($_POST['redirect_to'] ?? home_url());

            // Mark onboarding complete
            $this->database->complete_onboarding($user_id);

            // Log completion
            $this->database->log_user_action(
                $user_id,
                'onboarding_complete'
            );

            wp_send_json_success([
                'redirect_url' => $redirect_url
            ]);

        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Verify AJAX request validity
     */
    private function verify_ajax_request() {
        check_ajax_referer('investiq_onboarding_nonce', 'nonce');

        if (!is_user_logged_in()) {
            throw new Exception(__('يجب تسجيل الدخول أولاً', 'investiq'));
        }

        if (!current_user_can('read')) {
            throw new Exception(__('غير مصرح لك بتنفيذ هذا الإجراء', 'investiq'));
        }
    }

    /**
     * Validate step data
     */
    private function validate_step_data($step, $data) {
        switch ($step) {
            case 1: // Basic info
                return [
                    'full_name' => sanitize_text_field($data['full_name'] ?? ''),
                    'phone' => $this->validate_phone($data['phone'] ?? ''),
                    'investor_type' => $this->validate_investor_type($data['investor_type'] ?? 'individual')
                ];

            case 2: // Preferences
                return [
                    'preferred_cities' => $this->validate_cities($data['preferred_cities'] ?? []),
                    'preferred_sectors' => $this->validate_sectors($data['preferred_sectors'] ?? [])
                ];

            case 3: // Investment profile
                return [
                    'capital_range' => $this->validate_capital_range($data['capital_range'] ?? ''),
                    'risk_level' => $this->validate_risk_level($data['risk_level'] ?? 'medium'),
                    'investment_goals' => $this->validate_goals($data['investment_goals'] ?? [])
                ];

            default:
                throw new Exception(__('خطأ في بيانات الخطوة', 'investiq'));
        }
    }

    /**
     * Validate phone number
     */
    private function validate_phone($phone) {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleaned) < 10) {
            throw new Exception(__('رقم الهاتف يجب أن يحتوي على 10 أرقام على الأقل', 'investiq'));
        }
        return $cleaned;
    }

    /**
     * Validate investor type
     */
    private function validate_investor_type($type) {
        $valid_types = ['individual', 'company', 'institution'];
        if (!in_array($type, $valid_types, true)) {
            return 'individual';
        }
        return $type;
    }

    /**
     * Validate selected cities
     */
    private function validate_cities($cities) {
        if (!is_array($cities) || empty($cities)) {
            throw new Exception(__('يجب اختيار مدينة واحدة على الأقل', 'investiq'));
        }

        $valid_cities = array_column($this->database->get_cities(['is_active' => 1]), 'city_id');
        $validated = [];

        foreach ($cities as $city_id) {
            $city_id = absint($city_id);
            if (in_array($city_id, $valid_cities, true)) {
                $validated[] = $city_id;
            }
        }

        if (empty($validated)) {
            throw new Exception(__('يجب اختيار مدينة واحدة على الأقل', 'investiq'));
        }

        return $validated;
    }

    /**
     * Validate selected sectors
     */
    private function validate_sectors($sectors) {
        if (!is_array($sectors)) {
            return [];
        }

        $valid_sectors = array_column($this->database->get_sectors(['is_active' => 1]), 'sector_id');
        $validated = [];

        foreach ($sectors as $sector_id) {
            $sector_id = absint($sector_id);
            if (in_array($sector_id, $valid_sectors, true)) {
                $validated[] = $sector_id;
            }
        }

        return $validated;
    }

    /**
     * Validate capital range
     */
    private function validate_capital_range($range) {
        $valid_ranges = array_keys($this->get_capital_ranges());
        if (!in_array($range, $valid_ranges, true)) {
            throw new Exception(__('نطاق رأس المال المحدد غير صالح', 'investiq'));
        }
        return $range;
    }

    /**
     * Validate risk level
     */
    private function validate_risk_level($level) {
        $valid_levels = array_keys($this->get_risk_levels());
        if (!in_array($level, $valid_levels, true)) {
            return 'medium';
        }
        return $level;
    }

    /**
     * Validate investment goals
     */
    private function validate_goals($goals) {
        if (!is_array($goals)) {
            return [];
        }
        
        $valid_goals = ['growth', 'income', 'diversification', 'other'];
        return array_intersect($goals, $valid_goals);
    }

    /**
     * Get onboarding steps
     */
    private function get_onboarding_steps() {
        return [
            1 => [
                'title' => __('المعلومات الأساسية', 'investiq'),
                'description' => __('أخبرنا المزيد عنك', 'investiq')
            ],
            2 => [
                'title' => __('الاهتمامات', 'investiq'),
                'description' => __('اختر المدن والقطاعات المفضلة', 'investiq')
            ],
            3 => [
                'title' => __('الملف الاستثماري', 'investiq'),
                'description' => __('حدد تفضيلاتك الاستثمارية', 'investiq')
            ]
        ];
    }

    /**
     * Get current step based on progress
     */
    private function get_current_step($prefs) {
        if (empty($prefs)) {
            return 1;
        }

        if (!isset($prefs['full_name']) || empty($prefs['full_name'])) {
            return 1;
        }

        if (empty($prefs['preferred_cities'])) {
            return 2;
        }

        if (empty($prefs['risk_level'])) {
            return 3;
        }

        return 1; // Default to first step
    }

    /**
     * Get available risk levels
     */
    private function get_risk_levels() {
        return [
            'low' => __('منخفض', 'investiq'),
            'medium' => __('متوسط', 'investiq'),
            'high' => __('مرتفع', 'investiq')
        ];
    }

    /**
     * Get capital ranges
     */
    private function get_capital_ranges() {
        return [
            '50k-100k' => __('50,000 - 100,000 ر.س', 'investiq'),
            '100k-250k' => __('100,000 - 250,000 ر.س', 'investiq'),
            '250k-500k' => __('250,000 - 500,000 ر.س', 'investiq'),
            '500k-1m' => __('500,000 - 1,000,000 ر.س', 'investiq'),
            '1m+' => __('+1,000,000 ر.س', 'investiq')
        ];
    }

    /**
     * Render login prompt
     */
    private function render_login_prompt() {
        $login_url = wp_login_url(get_permalink());
        $register_url = wp_registration_url();

        ob_start(); ?>
        <div class="investiq-onboarding-login">
            <div class="investiq-alert investiq-alert--info">
                <h3><?php esc_html_e('مرحباً بك في InvestIQ', 'investiq'); ?></h3>
                <p><?php esc_html_e('يجب تسجيل الدخول للوصول إلى عملية الإعداد الأولي', 'investiq'); ?></p>
                <div class="investiq-onboarding-actions">
                    <a href="<?php echo esc_url($login_url); ?>" class="investiq-button">
                        <?php esc_html_e('تسجيل الدخول', 'investiq'); ?>
                    </a>
                    <a href="<?php echo esc_url($register_url); ?>" class="investiq-button investiq-button--secondary">
                        <?php esc_html_e('إنشاء حساب', 'investiq'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render template
     */
    private function render_template($template, $data = []) {
        $template_path = INVESTIQ_PLUGIN_DIR . "templates/onboarding/{$template}.php";
        
        if (!file_exists($template_path)) {
            return sprintf(
                '<div class="investiq-alert investiq-alert--error">%s</div>',
                __('خطأ في تحميل القالب', 'investiq')
            );
        }

        ob_start();
        extract($data);
        include $template_path;
        return ob_get_clean();
    }
}