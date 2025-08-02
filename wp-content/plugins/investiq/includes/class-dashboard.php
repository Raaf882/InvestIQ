<?php
/**
 * InvestIQ Dashboard Class
 * 
 * Handles all dashboard functionality including data display, maps, and AJAX endpoints
 * 
 * @package InvestIQ
 * @version 1.0.0
 */

if (!class_exists('InvestIQ_Dashboard')) {

    class InvestIQ_Dashboard {
        private $database;
        private $assets_loaded = false;

        public function __construct($database) {
            $this->database = $database;
            $this->init_hooks();
        }

        private function init_hooks() {
            add_shortcode('investiq_dashboard', [$this, 'render_dashboard']);
            add_action('wp_ajax_investiq_get_city_data', [$this, 'ajax_get_city_data']);
            add_action('wp_ajax_investiq_get_region_data', [$this, 'ajax_get_region_data']);
            add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        }

        public function load_assets() {
            global $post;
            if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'investiq_dashboard') && !$this->assets_loaded) {
                $this->load_styles();
                $this->load_scripts();
                $this->assets_loaded = true;
            }
        }

        private function load_styles() {
            wp_enqueue_style('investiq-dashboard', INVESTIQ_PLUGIN_URL . 'assets/css/main.css', [], INVESTIQ_VERSION);
            wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css', [], '1.7.1');
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [], '5.15.4');
        }

        private function load_scripts() {
            wp_enqueue_script('investiq-dashboard', INVESTIQ_PLUGIN_URL . 'assets/js/Main.js', ['jquery'], INVESTIQ_VERSION, true);
            wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', [], '1.7.1', true);
            wp_enqueue_script('investiq-map', INVESTIQ_PLUGIN_URL . 'assets/js/leaflet-map.js', ['jquery', 'leaflet'], INVESTIQ_VERSION, true);

            // لاحظ حذف 'cities' و 'projects' من هنا
            wp_localize_script('investiq-map', 'investiqData', [
                'ajax_url'  => admin_url('admin-ajax.php'),
                'nonce'     => wp_create_nonce('investiq_nonce'),
                'mapCenter' => [18.25, 42.50],
                'mapZoom'   => 8,
            ]);
        }

        public function render_dashboard($atts = []) {
            if (!current_user_can('read')) {
                return '<div class="investiq-error">' . esc_html__('You do not have permission to view this content.', 'investiq') . '</div>';
            }
            ob_start();
            include INVESTIQ_PLUGIN_DIR . 'templates/dashboard.php';
            return ob_get_clean();
        }

        // AJAX handler لجلب بيانات المدينة حسب الـ city_id
        public function ajax_get_city_data() {
            try {
                if (!check_ajax_referer('investiq_nonce', 'nonce', false)) {
                    throw new Exception(__('Security check failed', 'investiq'));
                }
                if (!current_user_can('read')) {
                    throw new Exception(__('Permission denied', 'investiq'));
                }
                $city_id = isset($_POST['city_id']) ? absint($_POST['city_id']) : 0;
                if ($city_id <= 0) {
                    throw new Exception(__('Invalid city ID', 'investiq'));
                }
                global $wpdb;
                $city = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}investiq_cities WHERE city_id = %d", $city_id));
                if (!$city) {
                    throw new Exception(__('City not found', 'investiq'));
                }
                wp_send_json_success($city);
            } catch (Exception $e) {
                wp_send_json_error(['message' => $e->getMessage()], 400);
            }
        }

        // AJAX handler لجلب جميع بيانات المنطقة (مدن، مشاريع، قطاعات)
        public function ajax_get_region_data() {
            try {
                if (!check_ajax_referer('investiq_nonce', 'nonce', false)) {
                    throw new Exception(__('Security check failed', 'investiq'));
                }
                if (!current_user_can('read')) {
                    throw new Exception(__('Permission denied', 'investiq'));
                }
                $response = [
                    'cities'   => $this->get_cities(),
                    'projects' => $this->get_projects(),
                    'sectors'  => $this->get_sectors()
                ];
                wp_send_json_success($response);
            } catch (Exception $e) {
                wp_send_json_error(['message' => $e->getMessage()], 400);
            }
        }

        private function get_cities() {
            $cache_key = 'investiq_cities_data';
            $cities = get_transient($cache_key);
            if (false === $cities) {
                global $wpdb;
                $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}investiq_cities WHERE region = 'عسير'", ARRAY_A);
                if (empty($cities)) {
                    $cities = $this->get_default_cities();
                }
                set_transient($cache_key, $cities, 6 * HOUR_IN_SECONDS);
            }
            return $cities;
        }

        private function get_projects() {
            $cache_key = 'investiq_projects_data';
            $projects = get_transient($cache_key);
            if (false === $projects) {
                global $wpdb;
                $projects = $wpdb->get_results(
                    "SELECT * FROM {$wpdb->prefix}investiq_projects WHERE region = 'عسير' AND status = 'active' ORDER BY created_at DESC LIMIT 50",
                    ARRAY_A
                );
                if (empty($projects)) {
                    $projects = $this->get_default_projects();
                }
                set_transient($cache_key, $projects, 6 * HOUR_IN_SECONDS);
            }
            return $projects;
        }

        private function get_sectors() {
            global $wpdb;
            return $wpdb->get_results(
                "SELECT sector, COUNT(*) as project_count, SUM(investment_value) as total_investment, AVG(expected_return) as avg_return
                 FROM {$wpdb->prefix}investiq_projects
                 WHERE region = 'عسير'
                 GROUP BY sector",
                ARRAY_A
            );
        }

        private function get_default_cities() {
            return [
                ['city_id' => 1, 'name' => 'أبها', 'region' => 'عسير', 'lat' => 18.2167, 'lng' => 42.5053, 'population' => 1000000, 'investment_rank' => 1],
                ['city_id' => 2, 'name' => 'خميس مشيط', 'region' => 'عسير', 'lat' => 18.3000, 'lng' => 42.7333, 'population' => 800000, 'investment_rank' => 2]
            ];
        }

        private function get_default_projects() {
            // يمكنك إضافة مشاريع افتراضية هنا إذا تريد
            return [];
        }
    }
}
