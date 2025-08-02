<?php
// Get user preferences (simulated data)
$preferences = array(
    'preferred_cities_names' => array('أبها', 'خميس مشيط'),
    'preferred_sectors_names' => array('التقنية', 'السياحة'),
    'capital_range' => '500,000 - 1,000,000 ر.س',
    'risk_level' => 'medium'
);

// Get personalized recommendations (simulated data)
$recommendations = array(
    array(
        'city_name' => 'خميس مشيط',
        'sector_name' => 'التقنية',
        'title' => 'مركز تطوير البرمجيات',
        'description' => 'فرصة استثمارية في مركز تطوير برمجيات متخصص في حلول الأعمال مع وجود كوادر مؤهلة في المنطقة.',
        'investment_amount' => '3,500,000 ر.س',
        'expected_return' => '22',
        'risk_level' => 'medium',
        'match_score' => 85,
        'opportunity_id' => 101
    ),
    // Add more recommendations...
);
?>
<div class="personalized-section">
    <h2 class="section-title">فرص مخصصة لك</h2>
    
    <div class="preferences-summary">
        <h3>تفضيلاتك الحالية:</h3>
        <div class="preferences-grid">
            <div class="preference-item">
                <span class="preference-label">المدن المفضلة:</span>
                <span class="preference-value">
                    <?php echo implode('، ', $preferences['preferred_cities_names']); ?>
                </span>
            </div>
            <div class="preference-item">
                <span class="preference-label">القطاعات المفضلة:</span>
                <span class="preference-value">
                    <?php echo implode('، ', $preferences['preferred_sectors_names']); ?>
                </span>
            </div>
            <div class="preference-item">
                <span class="preference-label">نطاق رأس المال:</span>
                <span class="preference-value">
                    <?php echo $preferences['capital_range']; ?>
                </span>
            </div>
            <div class="preference-item">
                <span class="preference-label">مستوى المخاطرة:</span>
                <span class="preference-value">
                    <?php 
                    switch ($preferences['risk_level']) {
                        case 'low':
                            echo 'منخفض';
                            break;
                        case 'high':
                            echo 'مرتفع';
                            break;
                        default:
                            echo 'متوسط';
                    }
                    ?>
                </span>
            </div>
        </div>
        <button class="btn-edit-preferences">تعديل التفضيلات</button>
    </div>
    
    <?php if (!empty($recommendations)): ?>
    <div class="personalized-recommendations">
        <h3>الفرص التي تناسب تفضيلاتك:</h3>
        
        <div class="recommendations-grid">
            <?php foreach ($recommendations as $opportunity): ?>
            <div class="opportunity-card">
                <div class="opportunity-header">
                    <span class="city"><?php echo esc_html($opportunity['city_name']); ?></span>
                    <span class="sector"><?php echo esc_html($opportunity['sector_name']); ?></span>
                    <?php if ($opportunity['match_score'] > 80): ?>
                    <span class="match-badge excellent">مطابقة ممتازة</span>
                    <?php elseif ($opportunity['match_score'] > 60): ?>
                    <span class="match-badge good">مطابقة جيدة</span>
                    <?php else: ?>
                    <span class="match-badge fair">مطابقة عادلة</span>
                    <?php endif; ?>
                </div>
                <h4 class="opportunity-title"><?php echo esc_html($opportunity['title']); ?></h4>
                <p class="opportunity-description"><?php echo esc_html($opportunity['description']); ?></p>
                <div class="opportunity-footer">
                    <div class="investment-info">
                        <span class="amount"><?php echo esc_html($opportunity['investment_amount']); ?></span>
                        <span class="return">عائد <?php echo esc_html($opportunity['expected_return']); ?>%</span>
                        <span class="risk">مخاطر <?php 
                            switch ($opportunity['risk_level']) {
                                case 'low':
                                    echo 'منخفض';
                                    break;
                                case 'high':
                                    echo 'مرتفع';
                                    break;
                                default:
                                    echo 'متوسط';
                            }
                        ?></span>
                    </div>
                    <div class="opportunity-actions">
                        <button class="btn-save-opportunity" data-opportunity-id="<?php echo esc_attr($opportunity['opportunity_id']); ?>">
                            حفظ
                        </button>
                        <button class="btn-view-opportunity" data-opportunity-id="<?php echo esc_attr($opportunity['opportunity_id']); ?>">
                            عرض التفاصيل
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="no-recommendations">
        <p>لا توجد فرص مطابقة لتفضيلاتك الحالية. يرجى تعديل تفضيلاتك أو التحقق لاحقاً.</p>
    </div>
    <?php endif; ?>
</div>