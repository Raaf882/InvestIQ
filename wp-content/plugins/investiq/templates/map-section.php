<section class="investiq-map-section">
    <div class="map-header">
        <h3>خريطة الفرص الاستثمارية</h3>
        <div class="map-filters">
            <select id="map-sector-filter">
                <option value="">جميع القطاعات</option>
                <?php foreach($sectors as $sector): ?>
                <option value="<?php echo $sector->slug; ?>">
                    <?php echo $sector->name; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="phase-toggle">
                <label><input type="checkbox" value="planning" checked> تخطيط</label>
                <label><input type="checkbox" value="construction" checked> إنشاء</label>
                <label><input type="checkbox" value="operational" checked> تشغيل</label>
            </div>
        </div>
    </div>
    <div id="investiq-map"></div>
    <div class="map-legend">
        <span class="legend planning"><i class="fas fa-map-marked-alt"></i> قيد التخطيط</span>
        <span class="legend construction"><i class="fas fa-hard-hat"></i> قيد الإنشاء</span>
        <span class="legend operational"><i class="fas fa-check-circle"></i> قيد التشغيل</span>
    </div>
</section>