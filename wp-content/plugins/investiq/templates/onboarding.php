<?php
/**
 * Onboarding template for InvestIQ
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get available cities and sectors
$cities = array(
    array(
        'city_id' => 1,
        'city_name' => 'أبها',
        'opportunities_count' => 24,
        'demand_level' => 'high'
    ),
    // Add more cities...
);

$sectors = array(
    array(
        'sector_id' => 1,
        'sector_name' => 'التقنية'
    ),
    // Add more sectors...
);
?>
<div class="investiq-onboarding" dir="rtl">
    <div class="onboarding-header">
        <h1>👋 مرحباً بك في InvestIQ</h1>
        <p>لنبدأ بتخصيص تجربتك الاستثمارية</p>
    </div>
    
    <form id="investiq-onboarding-form" class="onboarding-form">
        <div class="form-section">
            <h3>📍 المدن المفضلة</h3>
            <p>اختر المدن التي تهمك في منطقة عسير</p>
            <div class="cities-grid">
                <?php foreach ($cities as $city): ?>
                <label class="city-checkbox">
                    <input type="checkbox" name="preferred_cities[]" value="<?php echo esc_attr($city['city_id']); ?>">
                    <span class="city-name"><?php echo esc_html($city['city_name']); ?></span>
                    <span class="city-stats">
                        <span class="opportunities"><?php echo esc_html($city['opportunities_count']); ?> فرص</span>
                        <?php 
                        switch ($city['demand_level']) {
                            case 'high':
                                echo '<span class="demand-icon high">🔥</span>';
                                break;
                            case 'low':
                                echo '<span class="demand-icon low">⚠️</span>';
                                break;
                            default:
                                echo '<span class="demand-icon medium">🟢</span>';
                        }
                        ?>
                    </span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-section">
            <h3>💼 القطاعات المفضلة</h3>
            <p>اختر القطاعات التي تهمك</p>
            <div class="sectors-grid">
                <?php foreach ($sectors as $sector): ?>
                <label class="sector-checkbox">
                    <input type="checkbox" name="preferred_sectors[]" value="<?php echo esc_attr($sector['sector_id']); ?>">
                    <span class="sector-name"><?php echo esc_html($sector['sector_name']); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-section">
            <h3>💰 نطاق رأس المال</h3>
            <p>ما هو المبلغ التقريبي الذي ترغب في استثماره؟</p>
            <select name="capital_range" class="capital-select">
                <option value="">اختر نطاق رأس المال</option>
                <option value="50k-100k">50,000 - 100,000 ر.س</option>
                <option value="100k-250k">100,000 - 250,000 ر.س</option>
                <option value="250k-500k">250,000 - 500,000 ر.س</option>
                <option value="500k-1m">500,000 - 1,000,000 ر.س</option>
                <option value="1m+">أكثر من 1,000,000 ر.س</option>
            </select>
        </div>
        
        <div class="form-section">
            <h3>⚠️ مستوى المخاطرة</h3>
            <p>ما هو مستوى المخاطرة الذي تفضله؟</p>
            <div class="risk-levels">
                <label class="risk-option">
                    <input type="radio" name="risk_level" value="low">
                    <span class="risk-label">منخفض</span>
                    <span class="risk-description">عوائد أقل ولكن أكثر استقراراً</span>
                </label>
                <label class="risk-option">
                    <input type="radio" name="risk_level" value="medium" checked>
                    <span class="risk-label">متوسط</span>
                    <span class="risk-description">توازن بين العوائد والمخاطر</span>
                </label>
                <label class="risk-option">
                    <input type="radio" name="risk_level" value="high">
                    <span class="risk-label">مرتفع</span>
                    <span class="risk-description">عوائد أعلى مع مخاطر أكبر</span>
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="submit-btn">حفظ التفضيلات والبدء</button>
        </div>
    </form>
</div>