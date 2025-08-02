/**
 * InvestIQ Main Application Script
 * 
 * Handles core functionality including:
 * - Theme management
 * - Navigation
 * - Notifications
 * - Dashboard initialization
 * - Onboarding process
 * 
 * @version 1.0.0
 */

class InvestIQApp {
    constructor() {
        this.currentTheme = 'light';
        this.initElements();
        this.initTheme();
        this.initEventListeners();
        this.detectPageType();
    }

    /**
     * Cache DOM elements
     */
    initElements() {
        this.elements = {
            $themeToggle: $('#theme-toggle'),
            $body: $('body'),
            $navItems: $('.nav-item'),
            $contentSections: $('.content-section'),
            $pageTitle: $('#dashboard-page-title'),
            $toast: $('#investiq-toast'),
            $toastContent: $('#investiq-toast .toast-content')
        };
    }

    /**
     * Initialize theme settings
     */
    initTheme() {
        // Get saved theme or default to light
        this.currentTheme = localStorage.getItem('investiq_theme') || 'light';

        // Apply theme
        this.applyTheme(this.currentTheme);

        // Set toggle state
        if (this.currentTheme === 'dark') {
            this.elements.$themeToggle.prop('checked', true);
        }
    }

    /**
     * Apply theme to the page
     */
    applyTheme(theme) {
        this.elements.$body.attr('data-theme', theme);
        localStorage.setItem('investiq_theme', theme);
    }

    /**
     * Initialize event listeners
     */
    initEventListeners() {
        // Theme toggle
        this.elements.$themeToggle.on('change', () => {
            this.currentTheme = this.elements.$themeToggle.is(':checked') ? 'dark' : 'light';
            this.applyTheme(this.currentTheme);
        });

        // Navigation
        this.elements.$navItems.on('click', (e) => this.handleNavigation(e));
    }

    /**
     * Handle navigation between sections
     */
    handleNavigation(e) {
        const $target = $(e.currentTarget);
        const targetId = $target.data('target');
        const pageTitle = $target.find('.nav-text').text();

        // Update active nav item
        this.elements.$navItems.removeClass('active');
        $target.addClass('active');

        // Update active section
        this.elements.$contentSections.removeClass('active');
        $(`#${targetId}`).addClass('active');

        // Update page title
        this.elements.$pageTitle.text(pageTitle);
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'success', duration = 3000) {
        // Set content and type
        this.elements.$toastContent.html(message);
        this.elements.$toast
            .removeClass('success error warning')
            .addClass(type)
            .addClass('show');

        // Hide after duration
        setTimeout(() => {
            this.elements.$toast.removeClass('show');
        }, duration);
    }

    /**
     * Detect page type and initialize appropriate functionality
     */
    detectPageType() {
        if ($('.investiq-dashboard').length) {
            this.initDashboard();
        } else if ($('.investiq-onboarding').length) {
            this.initOnboarding();
        }
    }

    /**
     * Initialize dashboard functionality
     */
    initDashboard() {
        this.initCharts();
        this.initDashboardEventListeners();
    }

    /**
     * Initialize charts
     */
    initCharts() {
        // Demand by city chart
        if ($('#demand-by-city-chart').length) {
            this.createBarChart(
                '#demand-by-city-chart',
                ['أبها', 'خميس مشيط', 'بيشة', 'النماص', 'رجال ألمع'],
                [85, 72, 65, 78, 82],
                'مستوى الطلب',
                'rgba(76, 175, 80, 0.7)',
                { y: { beginAtZero: true, max: 100 } }
            );
        }

        // Return by sector chart
        if ($('#return-by-sector-chart').length) {
            this.createDoughnutChart(
                '#return-by-sector-chart',
                ['التقنية', 'العقارات', 'السياحة', 'التجزئة', 'الصناعة'],
                [18, 12, 15, 10, 14],
                'العائد المتوقع %',
                [
                    'rgba(33, 150, 243, 0.7)',
                    'rgba(76, 175, 80, 0.7)',
                    'rgba(255, 152, 0, 0.7)',
                    'rgba(156, 39, 176, 0.7)',
                    'rgba(244, 67, 54, 0.7)'
                ]
            );
        }

        // Opportunities distribution chart
        if ($('#opportunities-distribution-chart').length) {
            this.createPieChart(
                '#opportunities-distribution-chart',
                ['التقنية', 'العقارات', 'السياحة', 'التجزئة', 'الصناعة', 'أخرى'],
                [25, 20, 18, 15, 12, 10],
                'توزيع الفرص',
                [
                    'rgba(33, 150, 243, 0.7)',
                    'rgba(76, 175, 80, 0.7)',
                    'rgba(255, 152, 0, 0.7)',
                    'rgba(156, 39, 176, 0.7)',
                    'rgba(244, 67, 54, 0.7)',
                    'rgba(96, 125, 139, 0.7)'
                ]
            );
        }
    }

    /**
     * Create bar chart
     */
    createBarChart(selector, labels, data, label, bgColor, options = {}) {
        const ctx = document.querySelector(selector).getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: bgColor,
                    borderColor: bgColor.replace('0.7', '1'),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: options.scales || {},
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true
                    }
                }
            }
        });
    }

    /**
     * Create doughnut chart
     */
    createDoughnutChart(selector, labels, data, label, bgColors) {
        const ctx = document.querySelector(selector).getContext('2d');
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: bgColors,
                    borderColor: bgColors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true
                    }
                }
            }
        });
    }

    /**
     * Create pie chart
     */
    createPieChart(selector, labels, data, label, bgColors) {
        const ctx = document.querySelector(selector).getContext('2d');
        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: bgColors,
                    borderColor: bgColors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true
                    }
                }
            }
        });
    }

    /**
     * Initialize dashboard event listeners
     */
    initDashboardEventListeners() {
        // Save opportunity
        $(document).on('click', '.btn-save-opportunity', (e) => {
            const opportunityId = $(e.currentTarget).data('opportunity-id');
            
            $.ajax({
                url: investiq_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'save_opportunity',
                    opportunity_id: opportunityId,
                    nonce: investiq_vars.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showToast('تم حفظ الفرصة بنجاح');
                    } else {
                        this.showToast(response.data?.message || 'حدث خطأ أثناء حفظ الفرصة', 'error');
                    }
                },
                error: () => {
                    this.showToast('حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        });

        // Save preferences
        $('#investiq-settings-form').on('submit', (e) => {
            e.preventDefault();
            
            const formData = $(e.currentTarget).serialize();
            
            $.ajax({
                url: investiq_vars.ajax_url,
                type: 'POST',
                data: formData + '&action=save_user_preferences&nonce=' + investiq_vars.nonce,
                success: (response) => {
                    if (response.success) {
                        this.showToast('تم حفظ التفضيلات بنجاح');
                    } else {
                        this.showToast(response.data?.message || 'حدث خطأ أثناء حفظ التفضيلات', 'error');
                    }
                },
                error: () => {
                    this.showToast('حدث خطأ في الاتصال بالخادم', 'error');
                }
            });
        });
    }

    /**
     * Initialize onboarding process
     */
    initOnboarding() {
        // City selection
        $(document).on('click', '.city-checkbox', (e) => {
            const $checkbox = $(e.currentTarget);
            $checkbox.toggleClass('selected');
            $checkbox.find('input').prop('checked', !$checkbox.find('input').prop('checked'));
        });

        // Sector selection
        $(document).on('click', '.sector-checkbox', (e) => {
            const $checkbox = $(e.currentTarget);
            $checkbox.toggleClass('selected');
            $checkbox.find('input').prop('checked', !$checkbox.find('input').prop('checked'));
        });

        // Risk level selection
        $(document).on('click', '.risk-option', (e) => {
            const $option = $(e.currentTarget);
            $('.risk-option').removeClass('selected');
            $option.addClass('selected');
            $option.find('input').prop('checked', true);
        });

        // Form submission
        $('#investiq-onboarding-form').on('submit', (e) => {
            e.preventDefault();
            
            const formData = $(e.currentTarget).serialize();
            
            $.ajax({
                url: investiq_vars.ajax_url,
                type: 'POST',
                data: formData + '&action=complete_onboarding&nonce=' + investiq_vars.nonce,
                beforeSend: () => {
                    $('.onboarding-submit').prop('disabled', true).html(`
                        <span class="spinner"></span> جاري الحفظ...
                    `);
                },
                success: (response) => {
                    if (response.success) {
                        window.location.href = response.data.redirect_url || investiq_vars.home_url;
                    } else {
                        this.showToast(response.data?.message || 'حدث خطأ أثناء حفظ التفضيلات', 'error');
                        $('.onboarding-submit').prop('disabled', false).text('حفظ والمتابعة');
                    }
                },
                error: () => {
                    this.showToast('حدث خطأ في الاتصال بالخادم', 'error');
                    $('.onboarding-submit').prop('disabled', false).text('حفظ والمتابعة');
                }
            });
        });
    }
}

// Initialize the application when DOM is ready
jQuery(document).ready(function($) {
    // Make sure required variables are available
    if (typeof investiq_vars === 'undefined') {
        console.error('InvestIQ variables not defined');
        return;
    }

    // Initialize the app
    new InvestIQApp();
});