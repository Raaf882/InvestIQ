<?php
// Get user preferences (simulated data)
$preferences = array(
    'preferred_cities' => array(1, 2),
    'preferred_sectors' => array(1, 3),
    'capital_range' => '500k-1m',
    'risk_level' => 'medium'
);

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
<div class="settings-section">
    <h2 class="section-title">إعداداتي</h2>
    
    <form id="investiq-settings-form" class="settings-form">
        <div class="form-section">
            <h3>📍 المدن المفضلة</h3>
            <p>اختر المدن التي تريد متابعة فرصها الاستثمارية</p>
            <div class="cities-grid">
                <?php foreach ($cities as $city): 
                    $is_selected = in_array($city['city_id'], $preferences['preferred_cities']);
                ?>
                <label class="city-checkbox <?php echo $is_selected ? 'selected' : ''; ?>">
                    <input type="checkbox" name="preferred_cities[]" value="<?php echo esc_attr($city['city_id']); ?>" <?php echo $is_selected ? 'checked' : ''; ?>>
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
                <?php foreach ($sectors as $sector): 
                    $is_selected = in_array($sector['sector_id'], $preferences['preferred_sectors']);
                ?>
                <label class="sector-checkbox <?php echo $is_selected ? 'selected' : ''; ?>">
                    <input type="checkbox" name="preferred_sectors[]" value="<?php echo esc_attr($sector['sector_id']); ?>" <?php echo $is_selected ? 'checked' : ''; ?>>
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
                <option value="50k-100k" <?php echo $preferences['capital_range'] === '50k-100k' ? 'selected' : ''; ?>>50,000 - 100,000 ر.س</option>
                <option value="100k-250k" <?php echo $preferences['capital_range'] === '100k-250k' ? 'selected' : ''; ?>>100,000 - 250,000 ر.س</option>
                <option value="250k-500k" <?php echo $preferences['capital_range'] === '250k-500k' ? 'selected' : ''; ?>>250,000 - 500,000 ر.س</option>
                <option value="500k-1m" <?php echo $preferences['capital_range'] === '500k-1m' ? 'selected' : ''; ?>>500,000 - 1,000,000 ر.س</option>
                <option value="1m+" <?php echo $preferences['capital_range'] === '1m+' ? 'selected' : ''; ?>>أكثر من 1,000,000 ر.س</option>
            </select>
        </div>
        
        <div class="form-section">
            <h3>⚠️ مستوى المخاطرة</h3>
            <p>ما هو مستوى المخاطرة الذي تفضله؟</p>
            <div class="risk-levels">
                <label class="risk-option <?php echo $preferences['risk_level'] === 'low' ? 'selected' : ''; ?>">
                    <input type="radio" name="risk_level" value="low" <?php echo $preferences['risk_level'] === 'low' ? 'checked' : ''; ?>>
                    <span class="risk-label">منخفض</span>
                    <span class="risk-description">عوائد أقل ولكن أكثر استقراراً</span>
                </label>
                <label class="risk-option <?php echo $preferences['risk_level'] === 'medium' ? 'selected' : ''; ?>">
                    <input type="radio" name="risk_level" value="medium" <?php echo $preferences['risk_level'] === 'medium' || empty($preferences['risk_level']) ? 'checked' : ''; ?>>
                    <span class="risk-label">متوسط</span>
                    <span class="risk-description">توازن بين العوائد والمخاطر</span>
                </label>
                <label class="risk-option <?php echo $preferences['risk_level'] === 'high' ? 'selected' : ''; ?>">
                    <input type="radio" name="risk_level" value="high" <?php echo $preferences['risk_level'] === 'high' ? 'checked' : ''; ?>>
                    <span class="risk-label">مرتفع</span>
                    <span class="risk-description">عوائد أعلى مع مخاطر أكبر</span>
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-save-settings">حفظ التغييرات</button>
        </div>
    </form>
</div>