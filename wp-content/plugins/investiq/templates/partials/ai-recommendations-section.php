<?php
// Get AI recommendations (simulated data)
$recommendations = array(
    'top_recommendation' => array(
        'city_name' => 'خميس مشيط',
        'sector_name' => 'التقنية',
        'title' => 'مركز تطوير البرمجيات',
        'description' => 'فرصة استثمارية في مركز تطوير برمجيات متخصص في حلول الأعمال مع وجود كوادر مؤهلة في المنطقة. المشروع يتضمن بناء مركز متكامل لتطوير البرمجيات مع مختبرات تدريب وتطوير. العائد المتوقع بناءً على دراسات الجدوى يشير إلى نمو سنوي مرتفع في قطاع التقنية بالمنطقة.',
        'investment_amount' => '3,500,000 ر.س',
        'expected_return' => '22',
        'risk_level' => 'medium',
        'confidence' => '87',
        'reason' => 'بناءً على اهتمامك بقطاع التقنية ومدينة خميس مشيط، هذه الفرصة توفر عائداً مرتفعاً مع مخاطر متوسطة. تحليلنا يشير إلى أن هذه الفرصة تتماشى تماماً مع تفضيلاتك الاستثمارية وتاريخ تصفحك للفرص المشابهة.',
        'opportunity_id' => 101
    ),
    'other_recommendations' => array(
        array(
            'city_name' => 'أبها',
            'sector_name' => 'السياحة',
            'title' => 'منتجع جبلي',
            'description' => 'فرصة استثمارية في منتجع جبلي فاخر بإطلالة بانورامية على جبال السروات.',
            'investment_amount' => '4,200,000 ر.س',
            'expected_return' => '18',
            'risk_level' => 'medium',
            'confidence' => '78',
            'reason' => 'تطابق مع اهتمامك بقطاع السياحة وزياراتك المتكررة لفرص أبها',
            'opportunity_id' => 102
        ),
        // Add more recommendations...
    )
);
?>
<div class="ai-recommendations-section">
    <h2 class="section-title">توصيات الذكاء الاصطناعي</h2>
    
    <?php if (!empty($recommendations)): ?>
    <div class="ai-recommendation-main">
        <div class="recommendation-content">
            <h3>أفضل توصية لك اليوم</h3>
            <p><?php echo esc_html($recommendations['top_recommendation']['reason']); ?></p>
            
            <div class="recommended-opportunity">
                <div class="opportunity-header">
                    <span class="city"><?php echo esc_html($recommendations['top_recommendation']['city_name']); ?></span>
                    <span class="sector"><?php echo esc_html($recommendations['top_recommendation']['sector_name']); ?></span>
                    <span class="confidence">ثقة: <?php echo esc_html($recommendations['top_recommendation']['confidence']); ?>%</span>
                </div>
                <h4 class="opportunity-title"><?php echo esc_html($recommendations['top_recommendation']['title']); ?></h4>
                <p class="opportunity-description"><?php echo esc_html($recommendations['top_recommendation']['description']); ?></p>
                <div class="opportunity-stats">
                    <div class="stat-item">
                        <span class="stat-label">مبلغ الاستثمار</span>
                        <span class="stat-value"><?php echo esc_html($recommendations['top_recommendation']['investment_amount']); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">العائد المتوقع</span>
                        <span class="stat-value"><?php echo esc_html($recommendations['top_recommendation']['expected_return']); ?>%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">مستوى المخاطرة</span>
                        <span class="stat-value"><?php 
                            switch ($recommendations['top_recommendation']['risk_level']) {
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
                </div>
                <div class="opportunity-actions">
                    <button class="btn-save-opportunity" data-opportunity-id="<?php echo esc_attr($recommendations['top_recommendation']['opportunity_id']); ?>">
                        حفظ هذه الفرصة
                    </button>
                    <button class="btn-view-opportunity" data-opportunity-id="<?php echo esc_attr($recommendations['top_recommendation']['opportunity_id']); ?>">
                        عرض التفاصيل الكاملة
                    </button>
                </div>
            </div>
        </div>
        
        <div class="other-recommendations">
            <h3>توصيات أخرى قد تهمك</h3>
            
            <div class="recommendations-list">
                <?php foreach ($recommendations['other_recommendations'] as $recommendation): ?>
                <div class="recommendation-item">
                    <div class="recommendation-header">
                        <span class="city"><?php echo esc_html($recommendation['city_name']); ?></span>
                        <span class="sector"><?php echo esc_html($recommendation['sector_name']); ?></span>
                        <span class="confidence">ثقة: <?php echo esc_html($recommendation['confidence']); ?>%</span>
                    </div>
                    <h4 class="recommendation-title"><?php echo esc_html($recommendation['title']); ?></h4>
                    <p class="recommendation-reason"><?php echo esc_html($recommendation['reason']); ?></p>
                    <div class="recommendation-actions">
                        <button class="btn-view-opportunity" data-opportunity-id="<?php echo esc_attr($recommendation['opportunity_id']); ?>">
                            عرض
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="no-recommendations">
        <p>لا توجد توصيات متاحة حالياً. يرجى التحقق لاحقاً أو تعديل تفضيلاتك للحصول على توصيات أكثر دقة.</p>
    </div>
    <?php endif; ?>
</div>