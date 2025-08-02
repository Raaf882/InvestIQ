/**
 * InvestIQ Map Module
 * 
 * Handles interactive map functionality including:
 * - Asir region visualization
 * - Investment project markers
 * - City data points
 * - Interactive popups
 * 
 * @version 1.0.0
 */

class InvestIQMap {
    constructor() {
        this.map = null;
        this.markerGroups = {};
        this.initMap();
        this.initEventListeners();
    }

    /**
     * Initialize the map
     */
    initMap() {
        // Create map instance
        this.map = L.map('investiq-asir-map', {
            center: investiqMapData.mapCenter || [19.0965, 42.8633],
            zoom: investiqMapData.mapZoom || 9,
            zoomControl: false,
            attributionControl: false
        });

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 13,
            minZoom: 7
        }).addTo(this.map);

        // Add controls
        this.addMapControls();
        
        // Add region boundary
        this.addRegionBoundary();

        // Load data
        this.loadMapData();
    }

    /**
     * Add map controls
     */
    addMapControls() {
        // Custom zoom control
        L.control.zoom({
            position: 'topright',
            zoomInText: '+',
            zoomOutText: '-'
        }).addTo(this.map);

        // Scale control
        L.control.scale({
            position: 'bottomleft',
            imperial: false,
            metric: true
        }).addTo(this.map);

        // Custom attribution
        L.control.attribution({
            position: 'bottomright',
            prefix: '<a href="https://investiq.sa" target="_blank">InvestIQ</a>'
        }).addTo(this.map);
    }

    /**
     * Add Asir region boundary
     */
    addRegionBoundary() {
        const boundaryStyle = {
            color: '#3388ff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.05
        };

        this.regionLayer = L.geoJSON(
            investiqMapData.asirGeoJSON || this.getDefaultGeoJSON(), 
            { style: boundaryStyle }
        ).addTo(this.map);

        // Fit bounds to region
        if (this.regionLayer.getBounds().isValid()) {
            this.map.fitBounds(this.regionLayer.getBounds());
        }
    }

    /**
     * Load map data from API
     */
    loadMapData() {
        this.showLoading();

        fetch(investiqMapData.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_asir_region_data',
                nonce: investiqMapData.nonce
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.processMapData(data.data);
            } else {
                throw new Error(data.data?.message || 'Invalid data format');
            }
        })
        .catch(error => {
            console.error('Error loading map data:', error);
            this.showError(error.message);
            this.loadDefaultData();
        })
        .finally(() => {
            this.hideLoading();
        });
    }

    /**
     * Process map data from API
     */
    processMapData(data) {
        // Clear existing markers
        this.clearMarkers();

        // Process cities
        if (data.cities && data.cities.length) {
            this.addCities(data.cities);
        }

        // Process projects
        if (data.projects && data.projects.length) {
            this.addProjects(data.projects);
        }

        // Process sectors
        if (data.sectors && data.sectors.length) {
            this.addSectorLayers(data.sectors);
        }
    }

    /**
     * Add cities to map
     */
    addCities(cities) {
        this.markerGroups.cities = L.layerGroup().addTo(this.map);

        cities.forEach(city => {
            const marker = L.marker([city.lat, city.lng], {
                icon: this.createCityIcon(city),
                zIndexOffset: 1000
            });

            marker.bindPopup(this.createCityPopup(city));
            marker.addTo(this.markerGroups.cities);
        });
    }

    /**
     * Add projects to map
     */
    addProjects(projects) {
        this.markerGroups.projects = L.layerGroup().addTo(this.map);

        projects.forEach(project => {
            const marker = L.marker([project.lat, project.lng], {
                icon: this.createProjectIcon(project)
            });

            marker.bindPopup(this.createProjectPopup(project));
            marker.addTo(this.markerGroups.projects);
        });

        // Add layer control
        this.addLayerControl();
    }

    /**
     * Add sector layers
     */
    addSectorLayers(sectors) {
        this.markerGroups.sectors = {};

        sectors.forEach(sector => {
            this.markerGroups.sectors[sector.sector_id] = L.layerGroup();
            
            // Add projects for this sector
            if (sector.projects) {
                sector.projects.forEach(project => {
                    const marker = L.marker([project.lat, project.lng], {
                        icon: this.createProjectIcon(project)
                    });
                    
                    marker.bindPopup(this.createProjectPopup(project));
                    marker.addTo(this.markerGroups.sectors[sector.sector_id]);
                });
            }
        });

        // Add layer control for sectors
        this.addLayerControl();
    }

    /**
     * Create city marker icon
     */
    createCityIcon(city) {
        const size = Math.min(30 + (city.importance * 5), 60);
        
        return L.divIcon({
            className: 'city-marker',
            html: `
                <div class="city-marker-inner" style="width:${size}px;height:${size}px">
                    <span>${city.name}</span>
                </div>
            `,
            iconSize: [size, size],
            iconAnchor: [size/2, size/2]
        });
    }

    /**
     * Create project marker icon
     */
    createProjectIcon(project) {
        const colors = {
            'tourism': '#4CAF50',
            'realestate': '#2196F3',
            'industrial': '#FF9800',
            'agriculture': '#8BC34A',
            'education': '#9C27B0',
            'default': '#607D8B'
        };

        const icons = {
            'tourism': 'fa-umbrella-beach',
            'realestate': 'fa-home',
            'industrial': 'fa-industry',
            'agriculture': 'fa-tractor',
            'education': 'fa-graduation-cap',
            'default': 'fa-project-diagram'
        };

        const color = colors[project.type] || colors.default;
        const icon = icons[project.type] || icons.default;

        return L.divIcon({
            className: `project-marker ${project.type}`,
            html: `
                <div class="marker-pin" style="background:${color}">
                    <i class="fas ${icon}"></i>
                </div>
                ${project.is_new ? '<div class="new-badge">جديد</div>' : ''}
            `,
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });
    }

    /**
     * Create city popup content
     */
    createCityPopup(city) {
        return `
            <div class="map-popup city-popup">
                <h3>${city.name}</h3>
                <div class="popup-content">
                    <div class="popup-row">
                        <i class="fas fa-project-diagram"></i>
                        <span>${city.project_count || 0} مشاريع</span>
                    </div>
                    <div class="popup-row">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>${this.formatCurrency(city.total_investment || 0)}</span>
                    </div>
                    <div class="popup-row">
                        <i class="fas fa-percentage"></i>
                        <span>متوسط العائد: ${city.avg_return || 0}%</span>
                    </div>
                </div>
                <div class="popup-footer">
                    <button class="view-city" data-city-id="${city.city_id}">
                        <i class="fas fa-info-circle"></i> التفاصيل
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Create project popup content
     */
    createProjectPopup(project) {
        return `
            <div class="map-popup project-popup">
                <div class="popup-header ${project.type}">
                    <h3>${project.title}</h3>
                    ${project.is_new ? '<span class="new-badge">جديد</span>' : ''}
                </div>
                <div class="popup-content">
                    ${project.image ? `
                        <div class="popup-image">
                            <img src="${project.image}" alt="${project.title}">
                        </div>
                    ` : ''}
                    <div class="popup-details">
                        <div class="detail-row">
                            <i class="fas fa-city"></i>
                            <span>${project.city}</span>
                        </div>
                        <div class="detail-row">
                            <i class="fas fa-tag"></i>
                            <span>${this.getTypeName(project.type)}</span>
                        </div>
                        <div class="detail-row">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>${this.formatCurrency(project.investment_value)}</span>
                        </div>
                        <div class="detail-row">
                            <i class="fas fa-percentage"></i>
                            <span>العائد: ${project.expected_return}%</span>
                        </div>
                        <div class="detail-row">
                            <i class="fas fa-calendar-alt"></i>
                            <span>ينتهي: ${project.completion_date}</span>
                        </div>
                    </div>
                </div>
                <div class="popup-footer">
                    <button class="view-project" data-project-id="${project.id}">
                        <i class="fas fa-info-circle"></i> التفاصيل الكاملة
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Add layer control
     */
    addLayerControl() {
        const baseLayers = {};
        const overlays = {
            'المدن': this.markerGroups.cities,
            'المشاريع': this.markerGroups.projects
        };

        // Add sector layers if available
        if (this.markerGroups.sectors) {
            Object.entries(this.markerGroups.sectors).forEach(([id, layer]) => {
                const sectorName = this.getSectorName(id);
                overlays[`قطاع ${sectorName}`] = layer;
            });
        }

        L.control.layers(baseLayers, overlays, {
            position: 'topright',
            collapsed: false
        }).addTo(this.map);
    }

    /**
     * Get sector name by ID
     */
    getSectorName(sectorId) {
        // In a real implementation, this would come from your data
        const sectors = {
            '1': 'السياحة',
            '2': 'العقارات',
            '3': 'الصناعة',
            '4': 'الزراعة',
            '5': 'التعليم'
        };
        return sectors[sectorId] || `قطاع ${sectorId}`;
    }

    /**
     * Get type name
     */
    getTypeName(type) {
        const types = {
            'tourism': 'سياحي',
            'realestate': 'عقاري',
            'industrial': 'صناعي',
            'agriculture': 'زراعي',
            'education': 'تعليمي',
            'default': 'استثماري'
        };
        return types[type] || types.default;
    }

    /**
     * Format currency
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: 'SAR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    /**
     * Show loading state
     */
    showLoading() {
        const loader = document.createElement('div');
        loader.className = 'map-loader';
        loader.innerHTML = `
            <div class="spinner"></div>
            <p>جاري تحميل البيانات...</p>
        `;
        document.getElementById('investiq-asir-map').appendChild(loader);
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        const loader = document.querySelector('.map-loader');
        if (loader) loader.remove();
    }

    /**
     * Show error message
     */
    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'map-error';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            <p>${message}</p>
            <button class="retry-btn">إعادة المحاولة</button>
        `;
        
        errorDiv.querySelector('.retry-btn').addEventListener('click', () => {
            errorDiv.remove();
            this.loadMapData();
        });

        document.getElementById('investiq-asir-map').appendChild(errorDiv);
    }

    /**
     * Clear all markers
     */
    clearMarkers() {
        Object.values(this.markerGroups).forEach(group => {
            if (group instanceof L.LayerGroup) {
                group.clearLayers();
            } else if (typeof group === 'object') {
                Object.values(group).forEach(layer => layer.clearLayers());
            }
        });
    }

    /**
     * Load default data when API fails
     */
    loadDefaultData() {
        const defaultData = {
            cities: investiqMapData.defaultCities || [
                { 
                    city_id: 1,
                    name: "أبها", 
                    lat: 18.2167, 
                    lng: 42.5053, 
                    importance: 5, 
                    project_count: 8, 
                    total_investment: 125000000,
                    avg_return: 12.5
                }
            ],
            projects: [
                {
                    id: 1,
                    title: "فندق ومنتجع سياحي في أبها",
                    type: "tourism",
                    city: "أبها",
                    lat: 18.2300,
                    lng: 42.5100,
                    investment_value: 5000000,
                    expected_return: 18,
                    completion_date: "ديسمبر 2024",
                    is_new: true
                }
            ]
        };

        this.processMapData(defaultData);
    }

    /**
     * Get default GeoJSON for Asir region
     */
    getDefaultGeoJSON() {
        return {
            "type": "FeatureCollection",
            "features": [{
                "type": "Feature",
                "properties": { "name": "عسير" },
                "geometry": {
                    "type": "Polygon",
                    "coordinates": [[
                        [41.5, 17.5], [44.5, 17.8], [44.8, 20.5], 
                        [41.8, 20.8], [41.5, 17.5]
                    ]]
                }
            }]
        };
    }

    /**
     * Initialize event listeners
     */
    initEventListeners() {
        // Handle popup button clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.view-project')) {
                const projectId = e.target.closest('.view-project').dataset.projectId;
                this.handleProjectClick(projectId);
            } else if (e.target.closest('.view-city')) {
                const cityId = e.target.closest('.view-city').dataset.cityId;
                this.handleCityClick(cityId);
            }
        });

        // Handle map events
        this.map.on('zoomend', () => this.handleZoomChange());
    }

    /**
     * Handle project click
     */
    handleProjectClick(projectId) {
        console.log('Project clicked:', projectId);
        // Implement project details view
    }

    /**
     * Handle city click
     */
    handleCityClick(cityId) {
        console.log('City clicked:', cityId);
        // Implement city details view
    }

    /**
     * Handle zoom change
     */
    handleZoomChange() {
        const currentZoom = this.map.getZoom();
        // Adjust marker sizes or visibility based on zoom level
    }
}

// Initialize the map when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('investiq-asir-map')) {
        new InvestIQMap();
    }
});