jQuery(document).ready(function($) {
    // Toggle form containers
    $('#add-new-city').on('click', function() {
        $('#city-form-container').slideDown();
        $('#city-form')[0].reset();
        $('#city-id').val('');
    });
    
    $('#add-new-sector').on('click', function() {
        $('#sector-form-container').slideDown();
        $('#sector-form')[0].reset();
        $('#sector-id').val('');
    });
    
    $('#add-new-opportunity').on('click', function() {
        $('#opportunity-form-container').slideDown();
        $('#opportunity-form')[0].reset();
        $('#opportunity-id').val('');
    });
    
    // Cancel forms
    $('#cancel-city-form').on('click', function() {
        $('#city-form-container').slideUp();
    });
    
    $('#cancel-sector-form').on('click', function() {
        $('#sector-form-container').slideUp();
    });
    
    $('#cancel-opportunity-form').on('click', function() {
        $('#opportunity-form-container').slideUp();
    });
    
    // Edit city
    $('.edit-city').on('click', function() {
        const cityId = $(this).data('city-id');
        const row = $(this).closest('tr');
        
        $('#city-id').val(cityId);
        $('#city-name').val(row.find('td:eq(0)').text());
        $('#latitude').val(row.data('lat'));
        $('#longitude').val(row.data('lng'));
        $('#opportunities-count').val(row.find('td:eq(1)').text());
        $('#demand-level').val(row.data('demand'));
        $('#avg-return').val(row.find('td:eq(3)').text().replace('%', ''));
        $('#sectors').val(row.data('sectors'));
        $('#is-active').prop('checked', row.find('td:eq(4)').find('.status-active').length > 0);
        
        $('#city-form-container').slideDown();
    });
    
    // Edit sector
    $('.edit-sector').on('click', function() {
        const sectorId = $(this).data('sector-id');
        const row = $(this).closest('tr');
        
        $('#sector-id').val(sectorId);
        $('#sector-name').val(row.find('td:eq(0)').text());
        $('#sector-icon').val(row.data('icon'));
        $('#sector-is-active').prop('checked', row.find('td:eq(2)').find('.status-active').length > 0);
        
        $('#sector-form-container').slideDown();
    });
    
    // Edit opportunity
    $('.edit-opportunity').on('click', function() {
        const opportunityId = $(this).data('opportunity-id');
        const row = $(this).closest('tr');
        
        $('#opportunity-id').val(opportunityId);
        $('#opportunity-title').val(row.find('td:eq(0)').text());
        $('#city-id-select').val(row.data('city-id'));
        $('#sector-id-select').val(row.data('sector-id'));
        $('#opportunity-description').val(row.data('description'));
        $('#investment-amount').val(row.find('td:eq(3)').text());
        $('#expected-return').val(row.find('td:eq(4)').text().replace('%', ''));
        $('#risk-level').val(row.data('risk'));
        $('#opportunity-is-active').prop('checked', row.find('td:eq(6)').find('.status-active').length > 0);
        
        $('#opportunity-form-container').slideDown();
    });
    
    // Save city
    $('#city-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: investiq_admin_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=save_city_data&nonce=' + investiq_admin_vars.nonce,
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
    
    // Save sector
    $('#sector-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: investiq_admin_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=save_sector_data&nonce=' + investiq_admin_vars.nonce,
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
    
    // Delete city
    $('.delete-city').on('click', function() {
        if (!confirm('هل أنت متأكد من حذف هذه المدينة؟ سيتم حذف جميع الفرص المرتبطة بها.')) {
            return;
        }
        
        const cityId = $(this).data('city-id');
        
        $.ajax({
            url: investiq_admin_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_city',
                city_id: cityId,
                nonce: investiq_admin_vars.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
    
    // Delete sector
    $('.delete-sector').on('click', function() {
        if (!confirm('هل أنت متأكد من حذف هذا القطاع؟ سيتم حذف جميع الفرص المرتبطة به.')) {
            return;
        }
        
        const sectorId = $(this).data('sector-id');
        
        $.ajax({
            url: investiq_admin_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_sector',
                sector_id: sectorId,
                nonce: investiq_admin_vars.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
    
    // Delete opportunity
    $('.delete-opportunity').on('click', function() {
        if (!confirm('هل أنت متأكد من حذف هذه الفرصة؟')) {
            return;
        }
        
        const opportunityId = $(this).data('opportunity-id');
        
        $.ajax({
            url: investiq_admin_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_opportunity',
                opportunity_id: opportunityId,
                nonce: investiq_admin_vars.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
});