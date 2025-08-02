/**
 * InvestIQ Dashboard Script
 * 
 * Handles all frontend functionality for the InvestIQ dashboard including:
 * - Data loading
 * - UI interactions
 * - AJAX operations
 * 
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';

    const InvestIQDashboard = {
        /**
         * Initialize the dashboard
         */
        init: function() {
            this.cacheElements();
            this.setupEventListeners();
            this.loadInitialData();
            this.initMap();
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            this.elements = {
                $tabs: $('.investiq-tab'),
                $tabContents: $('.investiq-tab-content'),
                $cityContainer: $('#city-data-container'),
                $cityLoading: $('#city-data-loading'),
                $sectorContainer: $('#sector-data-container'),
                $sectorLoading: $('#sector-data-loading'),
                $mapContainer: $('#investiq-map-container'),
                $errorContainer: $('#dashboard-error-container')
            };
        },

        /**
         * Set up event listeners
         */
        setupEventListeners: function() {
            // Tab switching
            this.elements.$tabs.on('click', (e) => this.handleTabSwitch(e));

            // City details
            $(document).on('click', '.city-details-btn', (e) => this.showCityDetails(e));

            // Sector details
            $(document).on('click', '.sector-details-btn', (e) => this.showSectorDetails(e));

            // Map interactions
            $(document).on('click', '.map-marker', (e) => this.handleMapMarkerClick(e));
        },

        /**
         * Handle tab switching
         */
        handleTabSwitch: function(e) {
            e.preventDefault();
            const $tab = $(e.currentTarget);
            const tabId = $tab.data('tab');

            // Update active tab
            this.elements.$tabs.removeClass('active');
            $tab.addClass('active');

            // Show corresponding content
            this.elements.$tabContents.removeClass('active');
            $(`#${tabId}-data-container`).addClass('active');

            // Load data if not already loaded
            if (tabId === 'cities' && !$tab.hasClass('loaded')) {
                this.loadCities();
                $tab.addClass('loaded');
            } else if (tabId === 'sectors' && !$tab.hasClass('loaded')) {
                this.loadSectors();
                $tab.addClass('loaded');
            }
        },

        /**
         * Load initial dashboard data
         */
        loadInitialData: function() {
            this.loadCities();
            this.loadSectors();
            this.loadMarketIndicators();
        },

        /**
         * Initialize the map
         */
        initMap: function() {
            if (this.elements.$mapContainer.length === 0) return;

            // Initialize Leaflet map
            this.map = L.map('investiq-map-container').setView(
                investiqMapData.mapCenter, 
                investiqMapData.mapZoom
            );

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(this.map);

            // Add cities to map
            this.addCitiesToMap(investiqMapData.defaultCities);
        },

        /**
         * Add cities to the map
         */
        addCitiesToMap: function(cities) {
            if (!cities || !Array.isArray(cities)) return;

            cities.forEach(city => {
                const marker = L.marker([city.lat, city.lng], {
                    icon: L.divIcon({
                        className: 'map-marker',
                        html: `<div class="marker-content">
                                 <i class="fas fa-map-marker-alt"></i>
                                 <span class="marker-title">${city.name}</span>
                               </div>`,
                        iconSize: [30, 42],
                        iconAnchor: [15, 42]
                    })
                }).addTo(this.map);

                marker.bindPopup(`
                    <div class="map-popup">
                        <h4>${city.name}</h4>
                        <p>الفرص الاستثمارية: ${city.project_count}</p>
                        <p>إجمالي الاستثمارات: ${this.formatCurrency(city.total_investment)}</p>
                        <button class="button view-city-btn" data-city-id="${city.id}">
                            عرض التفاصيل
                        </button>
                    </div>
                `);
            });
        },

        /**
         * Format currency
         */
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: 'SAR',
                minimumFractionDigits: 0
            }).format(amount);
        },

        /**
         * Load cities data
         */
        loadCities: function() {
            this.showLoading(this.elements.$cityContainer, this.elements.$cityLoading);

            $.ajax({
                url: investiq_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'investiq_get_cities',
                    nonce: investiq_vars.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderCities(response.data);
                    } else {
                        this.showError(this.elements.$cityContainer, response.data.message || investiq_vars.strings.error);
                    }
                },
                error: () => {
                    this.showError(this.elements.$cityContainer, investiq_vars.strings.error);
                },
                complete: () => {
                    this.hideLoading(this.elements.$cityContainer, this.elements.$cityLoading);
                }
            });
        },

        /**
         * Render cities data
         */
        renderCities: function(cities) {
            if (!cities || cities.length === 0) {
                this.elements.$cityContainer.html(`
                    <div class="no-data-message">
                        <i class="fas fa-city"></i>
                        <p>${investiq_vars.strings.no_cities}</p>
                    </div>
                `);
                return;
            }

            let html = '<div class="cities-grid">';
            
            cities.forEach(city => {
                html += `
                    <div class="city-card" data-city-id="${city.city_id}">
                        <div class="city-card-header">
                            <h3 class="city-name">${city.city_name}</h3>
                            <span class="demand-badge ${city.demand_level}">
                                ${this.getDemandLevelText(city.demand_level)}
                            </span>
                        </div>
                        <div class="city-card-image">
                            <img src="${city.image || investiq_vars.default_city_image}" alt="${city.city_name}">
                        </div>
                        <div class="city-card-body">
                            <div class="city-stats">
                                <div class="stat-item">
                                    <div class="stat-value">${city.opportunities_count || 0}</div>
                                    <div class="stat-label">فرص استثمارية</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">${city.avg_return || '0'}%</div>
                                    <div class="stat-label">متوسط العائد</div>
                                </div>
                            </div>
                            <button class="button city-details-btn" data-city-id="${city.city_id}">
                                التفاصيل الكاملة
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            this.elements.$cityContainer.html(html);
        },

        /**
         * Load sectors data
         */
        loadSectors: function() {
            this.showLoading(this.elements.$sectorContainer, this.elements.$sectorLoading);

            $.ajax({
                url: investiq_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'investiq_get_sectors',
                    nonce: investiq_vars.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderSectors(response.data);
                    } else {
                        this.showError(this.elements.$sectorContainer, response.data.message || investiq_vars.strings.error);
                    }
                },
                error: () => {
                    this.showError(this.elements.$sectorContainer, investiq_vars.strings.error);
                },
                complete: () => {
                    this.hideLoading(this.elements.$sectorContainer, this.elements.$sectorLoading);
                }
            });
        },

        /**
         * Render sectors data
         */
        renderSectors: function(sectors) {
            if (!sectors || sectors.length === 0) {
                this.elements.$sectorContainer.html(`
                    <div class="no-data-message">
                        <i class="fas fa-chart-pie"></i>
                        <p>${investiq_vars.strings.no_sectors}</p>
                    </div>
                `);
                return;
            }

            let html = '<div class="sectors-grid">';
            
            sectors.forEach(sector => {
                html += `
                    <div class="sector-card" data-sector-id="${sector.sector_id}">
                        <div class="sector-card-icon">
                            <i class="fas fa-${sector.sector_icon || 'chart-pie'}"></i>
                        </div>
                        <div class="sector-card-body">
                            <h3 class="sector-name">${sector.sector_name}</h3>
                            <div class="sector-stats">
                                <div class="stat-item">
                                    <div class="stat-value">${sector.project_count || 0}</div>
                                    <div class="stat-label">مشاريع نشطة</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">${sector.avg_return || '0'}%</div>
                                    <div class="stat-label">متوسط العائد</div>
                                </div>
                            </div>
                            <button class="button sector-details-btn" data-sector-id="${sector.sector_id}">
                                عرض القطاع
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            this.elements.$sectorContainer.html(html);
        },

        /**
         * Load market indicators
         */
        loadMarketIndicators: function() {
            // Implementation for market indicators
        },

        /**
         * Show city details
         */
        showCityDetails: function(e) {
            e.preventDefault();
            const cityId = $(e.currentTarget).data('city-id');
            
            // Implement city details modal or view
            console.log('Showing details for city ID:', cityId);
        },

        /**
         * Show sector details
         */
        showSectorDetails: function(e) {
            e.preventDefault();
            const sectorId = $(e.currentTarget).data('sector-id');
            
            // Implement sector details modal or view
            console.log('Showing details for sector ID:', sectorId);
        },

        /**
         * Handle map marker click
         */
        handleMapMarkerClick: function(e) {
            e.preventDefault();
            const cityId = $(e.currentTarget).data('city-id');
            
            // Implement city details from map click
            console.log('Map marker clicked for city ID:', cityId);
        },

        /**
         * Get demand level text
         */
        getDemandLevelText: function(level) {
            const levels = {
                'high': 'طلب مرتفع',
                'medium': 'طلب متوسط',
                'low': 'طلب منخفض'
            };
            return levels[level] || level;
        },

        /**
         * Show loading state
         */
        showLoading: function($container, $loader) {
            $container.addClass('loading');
            $loader.show();
        },

        /**
         * Hide loading state
         */
        hideLoading: function($container, $loader) {
            $container.removeClass('loading');
            $loader.hide();
        },

        /**
         * Show error message
         */
        showError: function($container, message) {
            $container.html(`
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                    <button class="button retry-btn">إعادة المحاولة</button>
                </div>
            `);

            // Add retry functionality
            $container.find('.retry-btn').on('click', () => {
                if ($container.attr('id') === 'city-data-container') {
                    this.loadCities();
                } else if ($container.attr('id') === 'sector-data-container') {
                    this.loadSectors();
                }
            });
        }
    };

    // Initialize the dashboard
    InvestIQDashboard.init();
});