<?php
/**
 * Dashboard template for InvestIQ with modern CSS integration
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current user data
$current_user = wp_get_current_user();
$user_name = $current_user->display_name;
$user_avatar = get_avatar_url($current_user->ID, array('size' => 60));

// Get dashboard data
$stats = array(
    'total_opportunities' => 142,
    'avg_return' => '14.5',
    'highlight_opportunities' => array(
        array(
            'city_name' => 'أبها',
            'sector_name' => 'السياحة',
            'title' => 'فندق ومنتجع سياحي',
            'description' => 'فرصة استثمارية في فندق 5 نجوم بموقع مميز في أبها مع إطلالة على الجبال والمناطق الخضراء.',
            'investment_amount' => '5,000,000 ر.س',
            'expected_return' => '18',
            'risk_level' => 'medium',
            'image' => INVESTIQ_PLUGIN_URL . 'assets/images/hotel-opportunity.jpg'
        ),
        // Add more opportunities...
    )
);

// Get AI recommendations
$ai_recommendations = array(
    'top_recommendation' => array(
        'city_name' => 'خميس مشيط',
        'sector_name' => 'التقنية',
        'title' => 'مركز تطوير البرمجيات',
        'description' => 'فرصة استثمارية في مركز تطوير برمجيات متخصص في حلول الأعمال مع وجود كوادر مؤهلة في المنطقة.',
        'investment_amount' => '3,500,000 ر.س',
        'expected_return' => '22',
        'risk_level' => 'medium',
        'confidence' => '87',
        'reason' => 'بناءً على اهتمامك بقطاع التقنية ومدينة خميس مشيط، هذه الفرصة توفر عائداً مرتفعاً مع مخاطر متوسطة.'
    ),
    // Add other recommendations...
);
?>
<div class="investiq-dashboard" dir="rtl">
    <!-- Dashboard Layout -->
    <div class="dashboard-container">
        <!-- Sidebar - Modern Styled -->
        <aside class="dashboard-sidebar investiq-card">
            <div class="sidebar-header">
                <div class="user-avatar">
                    <img src="<?php echo esc_url($user_avatar); ?>" alt="صورة المستخدم" class="avatar-img">
                </div>
                <div class="user-info">
                    <span class="user-name"><?php echo esc_html($user_name); ?></span>
                    <span class="user-role">مستثمر</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item active" data-target="dashboard-overview">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🏠</span>
                            <span class="nav-text">نظرة عامة على السوق</span>
                        </a>
                    </li>
                    <li class="nav-item" data-target="cities">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🗺️</span>
                            <span class="nav-text">تصفح حسب الموقع</span>
                        </a>
                    </li>
                    <li class="nav-item" data-target="sectors">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">💼</span>
                            <span class="nav-text">تصفح حسب القطاع</span>
                        </a>
                    </li>
                    <li class="nav-item" data-target="market-indicators">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span class="nav-text">مؤشرات السوق</span>
                        </a>
                    </li>
                    <li class="nav-item" data-target="personalized">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🎯</span>
                            <span class="nav-text">فرص مخصصة لك</span>
                        </a>
                    </li>
                    <li class="nav-item" data-target="ai-recommendations">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">🧠</span>
                            <span class="nav-text">توصيات الذكاء الاصطناعي</span>
                        </a>
                    </li>
                    <li class="nav-item" data-target="settings">
                        <a href="#" class="nav-link">
                            <span class="nav-icon">⚙️</span>
                            <span class="nav-text">إعداداتي</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <button class="logout-btn investiq-btn investiq-btn-secondary">
                    <span class="btn-icon">🚪</span>
                    <span class="btn-text">تسجيل الخروج</span>
                </button>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Top Bar -->
            <header class="dashboard-topbar investiq-card">
                <div class="topbar-left">
                    <h1 class="page-title" id="dashboard-page-title">نظرة عامة على السوق</h1>
                </div>
                
                <div class="topbar-right">
                    <button class="notifications-btn investiq-btn investiq-btn-icon">
                        <span class="btn-icon">🔔</span>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <div class="theme-switcher">
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle">
                            <span class="slider round"></span>
                        </label>
                        <span class="theme-icon">🌙</span>
                    </div>
                </div>
            </header>
            
            <!-- Content Sections -->
            <div class="dashboard-content">
                <!-- Overview Section -->
                <section id="dashboard-overview" class="content-section active">
                    <!-- Welcome Card -->
                    <div class="welcome-card investiq-card investiq-card-highlight">
                        <div class="welcome-content">
                            <h2>مرحباً بعودتك، <?php echo esc_html($user_name); ?>!</h2>
                            <p>إليك أحدث التطورات والفرص في منطقة عسير</p>
                        </div>
                        <div class="welcome-stats">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo esc_html($stats['total_opportunities']); ?></div>
                                <div class="stat-label">فرصة استثمارية</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo esc_html($stats['avg_return']); ?>%</div>
                                <div class="stat-label">متوسط العائد</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- AI Recommendation -->
                    <div class="ai-recommendation-card investiq-card">
                        <div class="recommendation-icon">🧠</div>
                        <div class="recommendation-content">
                            <h3>توصية مخصصة لك</h3>
                            <p class="recommendation-reason"><?php echo esc_html($ai_recommendations['top_recommendation']['reason']); ?></p>
                            <div class="recommendation-actions">
                                <button class="investiq-btn investiq-btn-primary">استكشف الفرصة</button>
                                <button class="investiq-btn investiq-btn-outline">تعلم المزيد</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Market Highlights -->
                    <div class="market-highlights">
                        <h3 class="section-title">أبرز الفرص</h3>
                        
                        <div class="highlights-grid">
                            <?php foreach ($stats['highlight_opportunities'] as $opportunity): ?>
                            <div class="highlight-card investiq-card investiq-interactive-card">
                                <div class="highlight-header">
                                    <span class="city-tag"><?php echo esc_html($opportunity['city_name']); ?></span>
                                    <span class="sector-tag"><?php echo esc_html($opportunity['sector_name']); ?></span>
                                </div>
                                <?php if (!empty($opportunity['image'])): ?>
                                <div class="highlight-image" style="background-image: url('<?php echo esc_url($opportunity['image']); ?>')"></div>
                                <?php endif; ?>
                                <div class="highlight-content">
                                    <h4><?php echo esc_html($opportunity['title']); ?></h4>
                                    <p><?php echo esc_html($opportunity['description']); ?></p>
                                </div>
                                <div class="highlight-footer">
                                    <div class="investment-info">
                                        <span class="amount"><?php echo esc_html($opportunity['investment_amount']); ?></span>
                                        <span class="return">عائد <?php echo esc_html($opportunity['expected_return']); ?>%</span>
                                    </div>
                                    <button class="investiq-btn investiq-btn-outline">عرض التفاصيل</button>
                                </div>
                                <div class="investiq-interactive-content">
                                    <h4>معلومات إضافية</h4>
                                    <p>مستوى المخاطرة: <?php echo esc_html($opportunity['risk_level']); ?></p>
                                    <button class="investiq-btn investiq-btn-primary">استثمر الآن</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="recent-activity investiq-card">
                        <h3 class="section-title">نشاطك الأخير</h3>
                        
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon">👁️</div>
                                <div class="activity-content">
                                    <p>عرضت تفاصيل فرصة فندق ومنتجع سياحي في أبها</p>
                                    <span class="activity-time">منذ ساعتين</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">💾</div>
                                <div class="activity-content">
                                    <p>حفظت فرصة مركز تطوير البرمجيات في خميس مشيط</p>
                                    <span class="activity-time">منذ يوم واحد</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">🗺️</div>
                                <div class="activity-content">
                                    <p>استعرضت فرص مدينة بيشة</p>
                                    <span class="activity-time">منذ 3 أيام</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Cities Section -->
                <section id="cities" class="content-section">
                    <?php include_once('partials/cities-section.php'); ?>
                </section>
                
                <!-- Sectors Section -->
                <section id="sectors" class="content-section">
                    <?php include_once('partials/sectors-section.php'); ?>
                </section>
                
                <!-- Market Indicators Section -->
                <section id="market-indicators" class="content-section">
                    <?php include_once('partials/market-indicators-section.php'); ?>
                </section>
                
                <!-- Personalized Section -->
                <section id="personalized" class="content-section">
                    <?php include_once('partials/personalized-section.php'); ?>
                </section>
                
                <!-- AI Recommendations Section -->
                <section id="ai-recommendations" class="content-section">
                    <?php include_once('partials/ai-recommendations-section.php'); ?>
                </section>
                
                <!-- Settings Section -->
                <section id="settings" class="content-section">
                    <?php include_once('partials/settings-section.php'); ?>
                </section>
            </div>
        </main>
    </div>
    
    <!-- Toast Notification -->
    <div id="investiq-toast" class="investiq-toast">
        <div class="toast-content"></div>
    </div>
</div>