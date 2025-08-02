<div class="cities-section">
    <h2 class="section-title">تصفح الفرص حسب الموقع <span class="info-badge">18 مدينة رئيسية</span></h2>
    
    <div class="cities-filters">
        <div class="search-container">
            <input type="text" id="city-search" placeholder="ابحث عن مدينة...">
            <i class="fas fa-search"></i>
        </div>
        <div class="filter-container">
            <select id="city-demand-filter">
                <option value="">جميع مستويات الطلب</option>
                <option value="high">طلب عالي</option>
                <option value="medium">طلب متوسط</option>
                <option value="low">تشبع عالي</option>
            </select>
        </div>
        <div class="filter-container">
            <select id="sector-filter">
                <option value="">جميع القطاعات</option>
                <option value="real-estate">العقارات</option>
                <option value="tourism">السياحة</option>
                <option value="agriculture">الزراعة</option>
                <option value="healthcare">الرعاية الصحية</option>
                <option value="technology">التقنية</option>
                <option value="education">التعليم</option>
                <option value="industrial">الصناعة</option>
                <option value="retail">التجزئة</option>
            </select>
        </div>
        <div class="filter-container">
            <select id="investment-range">
                <option value="">جميع القيم</option>
                <option value="0-100">حتى 100 مليون</option>
                <option value="100-500">100 - 500 مليون</option>
                <option value="500-1000">500 مليون - 1 مليار</option>
                <option value="1000+">أكثر من 1 مليار</option>
            </select>
        </div>
    </div>
    
    <div class="cities-grid">
        <!-- Abha City Card -->
        <div class="city-card" data-city-id="1" data-demand="high" data-sectors="tourism,real-estate,technology,education" data-investment="high">
            <div class="city-header">
                <div class="city-thumbnail" style="background-image: url('<?php echo INVESTIQ_PLUGIN_URL; ?>assets/images/cities/abha-panorama.jpg')">
                    <div class="city-tag premium">متميز</div>
                </div>
                <h3>أبها <span class="city-population">1.2 مليون نسمة</span></h3>
                <div class="demand-indicator">
                    <span class="demand-badge high">طلب عالي</span>
                    <div class="demand-meter">
                        <div class="meter-fill" style="width: 92%"></div>
                    </div>
                </div>
            </div>
            <div class="city-stats">
                <div class="stat-item">
                    <span class="stat-value">68</span>
                    <span class="stat-label">فرصة استثمارية</span>
                </div>
                <div class="stat-item highlighted">
                    <span class="stat-value">14.8%</span>
                    <span class="stat-label">متوسط العائد</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">4.7B</span>
                    <span class="stat-label">ريال حجم الاستثمارات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">23</span>
                    <span class="stat-label">مشروع جديد 2024</span>
                </div>
            </div>
            <div class="city-sectors">
                <span class="sector-tag tourism"><i class="fas fa-umbrella-beach"></i> السياحة</span>
                <span class="sector-tag real-estate"><i class="fas fa-building"></i> العقارات</span>
                <span class="sector-tag technology"><i class="fas fa-microchip"></i> التقنية</span>
                <span class="sector-tag education"><i class="fas fa-graduation-cap"></i> التعليم</span>
                <span class="more-sectors" data-tooltip="الرياضة, النقل, الترفيه">+3</span>
            </div>
            <div class="city-highlights">
                <div class="highlight-item">
                    <i class="fas fa-trophy"></i>
                    <span>عاصمة السياحة العربية 2024</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-chart-line"></i>
                    <span>نمو اقتصادي 9.2% سنوياً</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-plane-departure"></i>
                    <span>مطار دولي جديد 2025</span>
                </div>
            </div>
            <div class="city-footer">
                <div class="investor-count">
                    <i class="fas fa-user-tie"></i>
                    <span>34 مستثمر نشط</span>
                </div>
                <button class="btn-view-city">
                    <span>استكشف الفرص</span>
                    <i class="fas fa-arrow-left"></i>
                    <div class="hover-effect"></div>
                </button>
            </div>
        </div>
        
        <!-- Khamis Mushait City Card -->
        <div class="city-card" data-city-id="2" data-demand="high" data-sectors="commercial,healthcare,education,industrial" data-investment="very-high">
            <div class="city-header">
                <div class="city-thumbnail" style="background-image: url('<?php echo INVESTIQ_PLUGIN_URL; ?>assets/images/cities/khamis-skyline.jpg')">
                    <div class="city-tag hot">ساخن</div>
                </div>
                <h3>خميس مشيط <span class="city-population">1.8 مليون نسمة</span></h3>
                <div class="demand-indicator">
                    <span class="demand-badge high">طلب عالي</span>
                    <div class="demand-meter">
                        <div class="meter-fill" style="width: 88%"></div>
                    </div>
                </div>
            </div>
            <div class="city-stats">
                <div class="stat-item">
                    <span class="stat-value">57</span>
                    <span class="stat-label">فرصة استثمارية</span>
                </div>
                <div class="stat-item highlighted">
                    <span class="stat-value">16.3%</span>
                    <span class="stat-label">متوسط العائد</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">6.2B</span>
                    <span class="stat-label">ريال حجم الاستثمارات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">31</span>
                    <span class="stat-label">مشروع جديد 2024</span>
                </div>
            </div>
            <div class="city-sectors">
                <span class="sector-tag commercial"><i class="fas fa-shopping-cart"></i> التجارة</span>
                <span class="sector-tag healthcare"><i class="fas fa-hospital"></i> الصحة</span>
                <span class="sector-tag education"><i class="fas fa-graduation-cap"></i> التعليم</span>
                <span class="sector-tag industrial"><i class="fas fa-industry"></i> الصناعة</span>
                <span class="more-sectors" data-tooltip="الخدمات اللوجستية, الأغذية, التصنيع">+3</span>
            </div>
            <div class="city-highlights">
                <div class="highlight-item">
                    <i class="fas fa-users"></i>
                    <span>أكبر تجمع سكاني في الجنوب</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-hospital"></i>
                    <span>7 مستشفيات تحت الإنشاء</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-train"></i>
                    <span>محطة سكك حديدية 2026</span>
                </div>
            </div>
            <div class="city-footer">
                <div class="investor-count">
                    <i class="fas fa-user-tie"></i>
                    <span>42 مستثمر نشط</span>
                </div>
                <button class="btn-view-city">
                    <span>استكشف الفرص</span>
                    <i class="fas fa-arrow-left"></i>
                    <div class="hover-effect"></div>
                </button>
            </div>
        </div>

        <!-- Add 10 more detailed city cards here -->
    </div>

    <div class="cities-view-all">
        <button class="btn-view-all">
            <span>عرض جميع المدن (18)</span>
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
</div>

<div class="dashboard-section asir-investment-map">
    <div class="section-header">
        <h2 class="section-title">خريطة الاستثمار التفاعلية في منطقة عسير</h2>
        <div class="section-badges">
            <span class="badge-new">جديد!</span>
            <span class="badge-updated">تم التحديث اليوم</span>
        </div>
    </div>
    
    <div class="map-controls">
        <div class="map-filter-group">
            <div class="map-filter">
                <label for="project-type-filter"><i class="fas fa-filter"></i> تصفية حسب نوع المشروع:</label>
                <select id="project-type-filter" multiple="multiple">
                    <option value="residential" selected>سكني</option>
                    <option value="commercial" selected>تجاري</option>
                    <option value="tourism" selected>سياحي</option>
                    <option value="health" selected>صحي</option>
                    <option value="agriculture" selected>زراعي</option>
                    <option value="industrial">صناعي</option>
                    <option value="educational">تعليمي</option>
                    <option value="entertainment">ترفيهي</option>
                    <option value="religious">ديني</option>
                    <option value="transportation">مواصلات</option>
                </select>
            </div>
            <div class="map-filter">
                <label for="project-status-filter"><i class="fas fa-tasks"></i> حالة المشروع:</label>
                <select id="project-status-filter" multiple="multiple">
                    <option value="planned" selected>مخطط</option>
                    <option value="under-construction" selected>قيد الإنشاء</option>
                    <option value="completed">مكتمل</option>
                    <option value="operational">تشغيلي</option>
                </select>
            </div>
        </div>
        
        <div class="map-stats-container">
            <div class="map-stats-tabs">
                <button class="stat-tab active" data-tab="overview">نظرة عامة</button>
                <button class="stat-tab" data-tab="sectors">حسب القطاع</button>
                <button class="stat-tab" data-tab="timeline">الجدول الزمني</button>
            </div>
            <div class="map-stats">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">214</span>
                        <span class="stat-label">مشروع استثماري</span>
                        <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 14% عن 2023</span>
                    </div>
                </div>
                <div class="stat-box primary">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">38.5B</span>
                        <span class="stat-label">ريال إجمالي الاستثمارات</span>
                        <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 22% عن 2023</span>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">18</span>
                        <span class="stat-label">مدينة ومحافظة</span>
                        <span class="stat-change neutral"><i class="fas fa-equals"></i> ثابت منذ 2022</span>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">127</span>
                        <span class="stat-label">مشروع قيد التنفيذ</span>
                        <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 31% عن 2023</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="map-container with-sidebar">
        <div id="investiq-asir-map" style="height: 750px; width: 100%; border-radius: 12px; border: 1px solid #e0e0e0;"></div>
        <div class="map-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-tabs">
                    <button class="sidebar-tab active" data-tab="featured"><i class="fas fa-crown"></i> مميز</button>
                    <button class="sidebar-tab" data-tab="recent"><i class="fas fa-clock"></i> حديث</button>
                    <button class="sidebar-tab" data-tab="largest"><i class="fas fa-expand"></i> الأكبر</button>
                </div>
                <button class="btn-close-sidebar"><i class="fas fa-times"></i></button>
            </div>
            <div class="sidebar-content">
                <div class="sidebar-pane active" data-pane="featured">
                    <!-- Featured Projects -->
                    <div class="project-card mega-project">
                        <div class="project-image" style="background-image: url('<?php echo INVESTIQ_PLUGIN_URL; ?>assets/images/projects/abha-residential.jpg')">
                            <span class="project-badge residential">سكني</span>
                            <span class="project-tag">مشروع ضخم</span>
                        </div>
                        <div class="project-details">
                            <div class="project-header">
                                <h4>مدينة أبها السكنية الذكية</h4>
                                <div class="project-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span>4.7</span>
                                </div>
                            </div>
                            <div class="project-meta">
                                <span><i class="fas fa-map-marker-alt"></i> أبها، السودة</span>
                                <span><i class="fas fa-money-bill-wave"></i> 2.4 مليار ريال</span>
                            </div>
                            <p class="project-description">
                                أول مدينة سكنية ذكية في الجنوب بمساحة 3.2 مليون م²، تتضمن 2500 وحدة سكنية، مركز تجاري، مدارس ذكية، 
                                مستشفى تخصصي، ومنتزهات بيئية. المشروع يحقق معايير الاستدامة والذكاء الاصطناعي في إدارة المرافق.
                            </p>
                            <div class="project-stats">
                                <div class="stat-item">
                                    <i class="fas fa-chart-line"></i>
                                    <span>عائد متوقع: <strong>17.5%</strong></span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>الانتهاء: <strong>Q3 2027</strong></span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-percentage"></i>
                                    <span>تمويل: <strong>60%</strong></span>
                                </div>
                            </div>
                            <div class="project-actions">
                                <button class="btn-view-details">
                                    <span>التفاصيل الكاملة</span>
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <button class="btn-save-project" data-tooltip="حفظ للمفضلة">
                                    <i class="far fa-bookmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add 4 more featured projects with similar detail -->
                </div>

                <div class="sidebar-pane" data-pane="recent">
                    <!-- Recently Added Projects -->
                    <div class="project-card new-project">
                        <div class="project-image" style="background-image: url('<?php echo INVESTIQ_PLUGIN_URL; ?>assets/images/projects/new-hospital.jpg')">
                            <span class="project-badge health">صحي</span>
                            <span class="project-tag">جديد اليوم</span>
                        </div>
                        <div class="project-details">
                            <h4>المجمع الطبي التخصصي بأبها</h4>
                            <div class="project-meta">
                                <span><i class="fas fa-map-marker-alt"></i> أبها، المفتاحة</span>
                                <span><i class="fas fa-money-bill-wave"></i> 950 مليون ريال</span>
                            </div>
                            <p class="project-description">
                                مشروع طبي متكامل على مساحة 150,000 م² يشمل مستشفى تخصصي (300 سرير)، مركز أبحاث طبية، 
                                كلية طب، ووحدات سكنية للكوادر الطبية. يتخصص في جراحة القلب والأورام.
                            </p>
                            <div class="project-footer">
                                <span class="project-date"><i class="fas fa-clock"></i> أضيف منذ 3 ساعات</span>
                                <button class="btn-quick-view">عرض سريع</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add 4 more recent projects -->
                </div>

                <div class="sidebar-pane" data-pane="largest">
                    <!-- Largest Projects -->
                    <div class="project-card">
                        <div class="project-image" style="background-image: url('<?php echo INVESTIQ_PLUGIN_URL; ?>assets/images/projects/mega-mall.jpg')">
                            <span class="project-badge commercial">تجاري</span>
                            <span class="project-tag">الأكبر في الجنوب</span>
                        </div>
                        <div class="project-details">
                            <h4>مول عسير العالمي</h4>
                            <div class="project-meta">
                                <span><i class="fas fa-map-marker-alt"></i> خميس مشيط، الدوادمي</span>
                                <span><i class="fas fa-money-bill-wave"></i> 3.8 مليار ريال</span>
                            </div>
                            <p class="project-description">
                                أكبر مركز تجاري وترفيهي في جنوب المملكة بمساحة 580,000 م²، يتضمن 800 محل تجاري، 120 مطعم، 
                                مدينة ألعاب داخلية، سينما 12 قاعة، فندق 5 نجوم، ومواقف لـ 15,000 سيارة.
                            </p>
                            <div class="project-size">
                                <div class="size-indicator">
                                    <div class="size-bar" style="width: 100%"></div>
                                </div>
                                <span>الحجم: 580,000 م²</span>
                            </div>
                            <button class="btn-view-details">
                                <span>التفاصيل الكاملة</span>
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Add 4 more largest projects -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="map-footer">
        <div class="map-legend-container">
            <div class="map-legend">
                <h3><i class="fas fa-key"></i> مفتاح الخريطة:</h3>
                <ul>
                    <li><span class="legend-icon residential"></span> سكني</li>
                    <li><span class="legend-icon commercial"></span> تجاري</li>
                    <li><span class="legend-icon tourism"></span> سياحي</li>
                    <li><span class="legend-icon health"></span> صحي</li>
                    <li><span class="legend-icon agriculture"></span> زراعي</li>
                    <li><span class="legend-icon industrial"></span> صناعي</li>
                    <li><span class="legend-icon educational"></span> تعليمي</li>
                    <li><span class="legend-icon entertainment"></span> ترفيهي</li>
                    <li><span class="legend-icon religious"></span> ديني</li>
                </ul>
            </div>
            
            <div class="map-status-legend">
                <h3><i class="fas fa-info-circle"></i> حالة المشاريع:</h3>
                <ul>
                    <li><span class="status-icon planned"></span> مخطط</li>
                    <li><span class="status-icon under-construction"></span> قيد الإنشاء</li>
                    <li><span class="status-icon completed"></span> مكتمل</li>
                    <li><span class="status-icon operational"></span> تشغيلي</li>
                </ul>
            </div>
        </div>
        
        <div class="investment-summary">
            <div class="summary-header">
                <h3><i class="fas fa-chart-pie"></i> تحليل استثمار عسير</h3>
                <div class="summary-actions">
                    <button class="btn-export"><i class="fas fa-file-export"></i> تصدير</button>
                    <button class="btn-compare"><i class="fas fa-balance-scale"></i> مقارنة</button>
                </div>
            </div>
            
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-value">18</span>
                        <span class="summary-label">مدينة مستهدفة</span>
                        <span class="summary-sublabel">تغطي 100% من المنطقة</span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-value">214</span>
                        <span class="summary-label">مشروع قائم</span>
                        <span class="summary-sublabel">127 قيد التنفيذ</span>
                    </div>
                </div>
                <div class="summary-item highlighted">
                    <div class="summary-icon">
                        <i class="fas fa-money-bill-trend-up"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-value">38.5B</span>
                        <span class="summary-label">ريال حجم الاستثمار</span>
                        <span class="summary-sublabel">+22% عن 2023</span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-value">143</span>
                        <span class="summary-label">مستثمر رئيسي</span>
                        <span class="summary-sublabel">32 مستثمر جديد</span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-value">12,850</span>
                        <span class="summary-label">فرصة عمل</span>
                        <span class="summary-sublabel">متوقعة بحلول 2026</span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-value">14.7%</span>
                        <span class="summary-label">متوسط العائد</span>
                        <span class="summary-sublabel">أعلى من المعدل الوطني</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sector-analysis">
        <div class="analysis-tabs">
            <button class="analysis-tab active" data-tab="sector-distribution">التوزيع القطاعي</button>
            <button class="analysis-tab" data-tab="investment-trends">اتجاهات الاستثمار</button>
            <button class="analysis-tab" data-tab="geographic-distribution">التوزيع الجغرافي</button>
            <button class="analysis-tab" data-tab="future-projects">المشاريع المستقبلية</button>
        </div>
        
        <div class="analysis-content">
            <div class="analysis-pane active" data-pane="sector-distribution">
                <div class="chart-container">
                    <canvas id="sector-distribution-chart"></canvas>
                </div>
                <div class="chart-legend">
                    <h4>توزيع المشاريع حسب القطاعات (2024)</h4>
                    <ul>
                        <li><span class="color-swatch residential"></span> سكني (32%) - 68 مشروع</li>
                        <li><span class="color-swatch commercial"></span> تجاري (28%) - 60 مشروع</li>
                        <li><span class="color-swatch tourism"></span> سياحي (18%) - 38 مشروع</li>
                        <li><span class="color-swatch health"></span> صحي (12%) - 26 مشروع</li>
                        <li><span class="color-swatch agriculture"></span> زراعي (7%) - 15 مشروع</li>
                        <li><span class="color-swatch industrial"></span> صناعي (3%) - 7 مشروع</li>
                    </ul>
                    <div class="chart-note">
                        <i class="fas fa-info-circle"></i> الأرقام تشمل المشاريع الجارية والمخطط لها حتى نهاية 2026
                    </div>
                </div>
            </div>
            
            <!-- Other analysis panes would go here -->
        </div>
    </div>
    
    <div class="investment-cta">
        <div class="cta-content">
            <h3>ابدأ رحلتك الاستثمارية في عسير اليوم</h3>
            <p>انضم إلى أكثر من 143 مستثمراً يستفيدون من الفرص الواعدة في أسرع منطقة نمواً في المملكة</p>
            <div class="cta-buttons">
                <button class="btn-cta-primary">
                    <span>سجل كمستثمر</span>
                    <i class="fas fa-user-tie"></i>
                </button>
                <button class="btn-cta-secondary">
                    <span>اطلب استشارة مجانية</span>
                    <i class="fas fa-headset"></i>
                </button>
            </div>
        </div>
        <div class="cta-image">
            <img src="<?php echo INVESTIQ_PLUGIN_URL; ?>assets/images/cta-investment.jpg" alt="الاستثمار في عسير">
        </div>
    </div>
</div>