<?php
/**
 * InvestIQ Database Handler
 * 
 * Handles all database operations including:
 * - Table creation
 * - Data insertion
 * - User preferences
 * - Activity logging
 * 
 * @package InvestIQ
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('InvestIQ_Database')) {

    class InvestIQ_Database {
        private $table_prefix;
        
        public function __construct() {
            global $wpdb;
            $this->table_prefix = $wpdb->prefix . 'investiq_';
        }
        
        /**
         * Plugin activation hook
         */
        public function activate() {
            $this->create_tables();
            $this->insert_default_data();
            $this->maybe_update_database();
        }
        
        /**
         * Plugin deactivation hook
         */
        public function deactivate() {
            // Optional: Add cleanup operations if needed
            // $this->cleanup_temp_data();
        }
        
        /**
         * Create all required database tables
         */
        private function create_tables() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            
            $tables = [
                'cities' => "CREATE TABLE {$this->table_prefix}cities (
                    city_id INT AUTO_INCREMENT PRIMARY KEY,
                    city_name VARCHAR(255) NOT NULL,
                    region VARCHAR(100) NOT NULL DEFAULT 'عسير',
                    latitude DECIMAL(10,6),
                    longitude DECIMAL(10,6),
                    demand_level VARCHAR(20) DEFAULT 'medium',
                    opportunities_count INT DEFAULT 0,
                    avg_return DECIMAL(5,2),
                    is_active TINYINT(1) DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    KEY idx_region (region),
                    KEY idx_demand (demand_level)
                ) $charset_collate;",
                
                'sectors' => "CREATE TABLE {$this->table_prefix}sectors (
                    sector_id INT AUTO_INCREMENT PRIMARY KEY,
                    sector_name VARCHAR(100) NOT NULL,
                    sector_icon VARCHAR(50),
                    description TEXT,
                    is_active TINYINT(1) DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY uniq_sector_name (sector_name)
                ) $charset_collate;",
                
                'opportunities' => "CREATE TABLE {$this->table_prefix}opportunities (
                    opportunity_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                    city_id INT NOT NULL,
                    sector_id INT NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    description TEXT,
                    investment_amount DECIMAL(15,2),
                    expected_return DECIMAL(5,2),
                    risk_level VARCHAR(20),
                    status VARCHAR(20) DEFAULT 'active',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    is_active TINYINT(1) DEFAULT 1,
                    FOREIGN KEY fk_city (city_id) REFERENCES {$this->table_prefix}cities(city_id),
                    FOREIGN KEY fk_sector (sector_id) REFERENCES {$this->table_prefix}sectors(sector_id),
                    KEY idx_city_sector (city_id, sector_id),
                    KEY idx_risk (risk_level)
                ) $charset_collate;",
                
                'user_preferences' => "CREATE TABLE {$this->table_prefix}user_preferences (
                    preference_id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id BIGINT NOT NULL,
                    preferred_cities TEXT,
                    preferred_sectors TEXT,
                    capital_range VARCHAR(50),
                    risk_level VARCHAR(20),
                    notification_prefs TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    onboarding_complete TINYINT(1) DEFAULT 0,
                    UNIQUE KEY uniq_user (user_id),
                    FOREIGN KEY fk_user (user_id) REFERENCES {$wpdb->prefix}users(ID)
                ) $charset_collate;",
                
                'user_logs' => "CREATE TABLE {$this->table_prefix}user_logs (
                    log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                    user_id BIGINT NOT NULL,
                    action_type VARCHAR(50) NOT NULL,
                    target_id BIGINT,
                    target_type VARCHAR(50),
                    details TEXT,
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    KEY idx_user_actions (user_id, action_type),
                    KEY idx_action_type (action_type),
                    KEY idx_created (created_at),
                    FOREIGN KEY fk_log_user (user_id) REFERENCES {$wpdb->prefix}users(ID)
                ) $charset_collate;"
            ];
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            
            foreach ($tables as $table_name => $sql) {
                if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_prefix}{$table_name}'") != $this->table_prefix . $table_name) {
                    dbDelta($sql);
                }
            }
        }
        
        /**
         * Insert default data into tables
         */
        public function insert_default_data() {
            global $wpdb;
            
            // Only insert if tables are empty
            if ($wpdb->get_var("SELECT COUNT(*) FROM {$this->table_prefix}sectors") == 0) {
                $sectors = [
                    ['sector_name' => 'التقنية', 'sector_icon' => 'laptop', 'description' => 'استثمارات في مجال التكنولوجيا والبرمجيات'],
                    ['sector_name' => 'العقارات', 'sector_icon' => 'home', 'description' => 'استثمارات في المجال العقاري والتطوير'],
                    ['sector_name' => 'السياحة', 'sector_icon' => 'umbrella-beach', 'description' => 'استثمارات في الفنادق والمنتجعات السياحية'],
                    ['sector_name' => 'التجزئة', 'sector_icon' => 'shopping-cart', 'description' => 'استثمارات في المراكز التجارية والمتاجر'],
                    ['sector_name' => 'الصناعة', 'sector_icon' => 'industry', 'description' => 'استثمارات في المصانع والمنشآت الصناعية'],
                    ['sector_name' => 'الخدمات', 'sector_icon' => 'concierge-bell', 'description' => 'استثمارات في الخدمات اللوجستية والاستشارية'],
                    ['sector_name' => 'الزراعة', 'sector_icon' => 'tractor', 'description' => 'استثمارات في المشاريع الزراعية والغذائية'],
                    ['sector_name' => 'التعليم', 'sector_icon' => 'graduation-cap', 'description' => 'استثمارات في المدارس والجامعات'],
                    ['sector_name' => 'الصحة', 'sector_icon' => 'hospital', 'description' => 'استثمارات في المستشفيات والمراكز الطبية'],
                    ['sector_name' => 'الطاقة', 'sector_icon' => 'bolt', 'description' => 'استثمارات في الطاقة المتجددة والتقليدية']
                ];
                
                foreach ($sectors as $sector) {
                    $wpdb->insert(
                        "{$this->table_prefix}sectors",
                        array_merge($sector, [
                            'created_at' => current_time('mysql'),
                            'updated_at' => current_time('mysql')
                        ]),
                        ['%s', '%s', '%s', '%s', '%s']
                    );
                }
            }
            
            // Insert default cities if empty
            if ($wpdb->get_var("SELECT COUNT(*) FROM {$this->table_prefix}cities") == 0) {
                $cities = [
                    [
                        'city_name' => 'أبها',
                        'latitude' => 18.2164,
                        'longitude' => 42.5053,
                        'opportunities_count' => 24,
                        'demand_level' => 'high',
                        'avg_return' => 12.5
                    ],
                    [
                        'city_name' => 'خميس مشيط',
                        'latitude' => 18.3000,
                        'longitude' => 42.7333,
                        'opportunities_count' => 18,
                        'demand_level' => 'medium',
                        'avg_return' => 10.2
                    ],
                    [
                        'city_name' => 'النماص',
                        'latitude' => 19.1500,
                        'longitude' => 42.1167,
                        'opportunities_count' => 8,
                        'demand_level' => 'low',
                        'avg_return' => 9.8
                    ]
                ];
                
                foreach ($cities as $city) {
                    $wpdb->insert(
                        "{$this->table_prefix}cities",
                        array_merge($city, [
                            'created_at' => current_time('mysql'),
                            'updated_at' => current_time('mysql'),
                            'is_active' => 1
                        ]),
                        ['%s', '%f', '%f', '%d', '%s', '%f', '%s', '%s', '%d']
                    );
                }
            }
        }
        
        /**
         * Handle database updates when plugin updates
         */
        private function maybe_update_database() {
            $current_version = get_option('investiq_db_version', '1.0.0');
            
            if (version_compare($current_version, INVESTIQ_VERSION, '<')) {
                // Future database updates would go here
                update_option('investiq_db_version', INVESTIQ_VERSION);
            }
        }
        
        /**
         * Log user activity
         * 
         * @param int $user_id
         * @param string $action_type
         * @param mixed $target_id
         * @param string $target_type
         * @param string $details
         * @return int|false
         */
        public function log_user_action($user_id, $action_type, $target_id = null, $target_type = null, $details = '') {
            global $wpdb;
            
            return $wpdb->insert(
                "{$this->table_prefix}user_logs",
                [
                    'user_id' => absint($user_id),
                    'action_type' => sanitize_text_field($action_type),
                    'target_id' => $target_id ? absint($target_id) : null,
                    'target_type' => $target_type ? sanitize_text_field($target_type) : null,
                    'details' => sanitize_textarea_field($details),
                    'ip_address' => $this->get_user_ip(),
                    'user_agent' => $this->get_user_agent(),
                    'created_at' => current_time('mysql')
                ],
                ['%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s']
            );
        }
        
        /**
         * Get user IP address
         */
        private function get_user_ip() {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $_SERVER['REMOTE_ADDR'];
        }
        
        /**
         * Get user agent
         */
        private function get_user_agent() {
            return isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 254) : '';
        }
        
        /**
         * Get user preferences
         * 
         * @param int $user_id
         * @return array|false
         */
        public function get_user_preferences($user_id) {
            global $wpdb;
            
            $prefs = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$this->table_prefix}user_preferences WHERE user_id = %d",
                    absint($user_id)
                ),
                ARRAY_A
            );
            
            if ($prefs) {
                $prefs['preferred_cities'] = maybe_unserialize($prefs['preferred_cities']);
                $prefs['preferred_sectors'] = maybe_unserialize($prefs['preferred_sectors']);
                $prefs['notification_prefs'] = maybe_unserialize($prefs['notification_prefs']);
                return $prefs;
            }
            
            return false;
        }
        
        /**
         * Update user preferences
         * 
         * @param int $user_id
         * @param array $preferences
         * @return bool
         */
        public function update_user_preferences($user_id, $preferences) {
            global $wpdb;
            
            $data = [
                'preferred_cities' => maybe_serialize($preferences['preferred_cities'] ?? []),
                'preferred_sectors' => maybe_serialize($preferences['preferred_sectors'] ?? []),
                'capital_range' => sanitize_text_field($preferences['capital_range'] ?? ''),
                'risk_level' => sanitize_text_field($preferences['risk_level'] ?? 'medium'),
                'notification_prefs' => maybe_serialize($preferences['notification_prefs'] ?? []),
                'updated_at' => current_time('mysql')
            ];
            
            if ($this->get_user_preferences($user_id)) {
                // Update existing
                return $wpdb->update(
                    "{$this->table_prefix}user_preferences",
                    $data,
                    ['user_id' => absint($user_id)],
                    ['%s', '%s', '%s', '%s', '%s', '%s'],
                    ['%d']
                );
            } else {
                // Insert new
                $data['user_id'] = absint($user_id);
                $data['created_at'] = current_time('mysql');
                
                return $wpdb->insert(
                    "{$this->table_prefix}user_preferences",
                    $data,
                    ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
                );
            }
        }
        
        /**
         * Mark user onboarding as complete
         * 
         * @param int $user_id
         * @return bool
         */
        public function complete_onboarding($user_id) {
            global $wpdb;
            
            return $wpdb->update(
                "{$this->table_prefix}user_preferences",
                [
                    'onboarding_complete' => 1,
                    'updated_at' => current_time('mysql')
                ],
                ['user_id' => absint($user_id)],
                ['%d', '%s'],
                ['%d']
            );
        }
        
        /**
         * Get investment opportunities with filters
         * 
         * @param array $filters
         * @return array
         */
        public function get_opportunities($filters = []) {
            global $wpdb;
            
            $defaults = [
                'city_id' => null,
                'sector_id' => null,
                'risk_level' => null,
                'min_return' => null,
                'limit' => 20,
                'offset' => 0
            ];
            
            $filters = wp_parse_args($filters, $defaults);
            
            $where = ["o.is_active = 1"];
            $params = [];
            
            if ($filters['city_id']) {
                $where[] = "o.city_id = %d";
                $params[] = absint($filters['city_id']);
            }
            
            if ($filters['sector_id']) {
                $where[] = "o.sector_id = %d";
                $params[] = absint($filters['sector_id']);
            }
            
            if ($filters['risk_level']) {
                $where[] = "o.risk_level = %s";
                $params[] = sanitize_text_field($filters['risk_level']);
            }
            
            if ($filters['min_return']) {
                $where[] = "o.expected_return >= %f";
                $params[] = floatval($filters['min_return']);
            }
            
            $where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            $query = $wpdb->prepare(
                "SELECT o.*, c.city_name, s.sector_name 
                 FROM {$this->table_prefix}opportunities o
                 JOIN {$this->table_prefix}cities c ON o.city_id = c.city_id
                 JOIN {$this->table_prefix}sectors s ON o.sector_id = s.sector_id
                 {$where_clause}
                 ORDER BY o.created_at DESC
                 LIMIT %d, %d",
                array_merge($params, [
                    absint($filters['offset']),
                    absint($filters['limit'])
                ])
            );
            
            return $wpdb->get_results($query, ARRAY_A);
        }
    }
}