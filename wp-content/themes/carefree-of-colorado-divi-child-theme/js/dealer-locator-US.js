jQuery(document).ready(function ($) {
     /* this function is use for USA dealer page */
     function USDealerFormSearch() {
        $('#ajax-rqst-loder').show();
            $.ajax({
                type: 'GET',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'US_dealer_locator',

                      /*   US_zipcode: US_zipcode,
                        US_city: US_city,
                        US_state: US_state,
                        US_aprox_range: US_aprox_range,
                        US_authorised_store: US_authorised_store,
                        US_rdva_member: US_rdva_member,
                        US_crtified_technician: US_crtified_technician,
                        US_crtified_personnel: US_crtified_personnel,
                        US_go_ring: US_go_ring,
                        US_mobile_service: US_mobile_service, */

                },
                success: function(response) {
                    console.log(response);
                    $('#ajax-rqst-loder').hide();
                    /* $('.US_dealer-rslt').html(response.data.US_rslt_data); */
                    var resultCountText = response.data.rslt_count + " dealers found matching your search criteria.";
                    $('.US_dealer-rslt').html('<p class="us-rslt-count-text">' + resultCountText + '</p>' + response.data.US_rslt_data);
                },
                error: function(error) {
                    console.log("error "+ error);
                    $('#ajax-rqst-loder').hide();
                    $('.US_dealer-rslt').html("Login limit reached in acumatica");
                    console.log(error);
                }
            });
    }
        /* call this function to show the results once page US Dealer page is loaded */
        USDealerFormSearch();
        
        $('#us-dealer').on('click', function(e) {
            var US_zipcode = jQuery('.US-zipcode').val();
            var US_city = jQuery('.US-city').val();
            var US_state = jQuery('.US-state').val();
            var US_aprox_range = jQuery('.US-approx-range').val();
            var US_authorised_store = jQuery('input[name="US_authorised_store[]"]:checked').val();
            var US_rdva_member = jQuery('input[name="US_rdva_member[]"]:checked').val();
            var US_crtified_technician = jQuery('input[name="US_rv_crtified[]"]:checked').val();
            var US_crtified_personnel = jQuery('input[name="US_rdva_crtified[]"]:checked').val();
            var US_go_ring = jQuery('input[name="US_go_ring[]"]:checked').val();
            var US_mobile_service = jQuery('input[name="US_mobile_service[]"]:checked').val();
            if(US_zipcode== "" && US_city =="" && US_state== ""){
                alert("Please provide criteria for either zip code or city and state");
                $('#us-dealer-link').removeAttr('href');            
                
            }
            else if(US_zipcode== "" && US_city !="" && US_state == ""){
                alert("Please provide state");
                $('#us-dealer-link').removeAttr('href');    
            }
            else if(US_zipcode== "" && US_city =="" && US_state != ""){
                alert("Please provide City");
                $('#us-dealer-link').removeAttr('href');    
            }
            else if(US_zipcode != "" && US_city !="" && US_state != ""){
                alert("Please Provide criteria for either zip code or city and state");
                $('#us-dealer-link').removeAttr('href');    
            }else{
                $('#us-dealer-link').attr('href', '#response_area');
                $('#ajax-rqst-loder').show();
                $.ajax({
                    type: 'GET',
                    url: MyAjax.ajaxurl,
                    data: {
                        action: 'US_dealer_locator',
                        US_zipcode: US_zipcode,
                        US_city: US_city,
                        US_state: US_state,
                        US_aprox_range: US_aprox_range,
                        US_authorised_store: US_authorised_store,
                        US_rdva_member: US_rdva_member,
                        US_crtified_technician: US_crtified_technician,
                        US_crtified_personnel: US_crtified_personnel,
                        US_go_ring: US_go_ring,
                        US_mobile_service: US_mobile_service,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#ajax-rqst-loder').hide();
                        /* $('.US_dealer-rslt').html(response.data.US_rslt_data); */
                        var resultCountText = response.data.rslt_count + " dealers found matching your search criteria.";
                         $('.US_dealer-rslt').html('<p class="us-rslt-count-text">' + resultCountText + '</p>' + response.data.US_rslt_data);
                    },
                    error: function(error) {
                        console.log("error "+ error);
                        $('#ajax-rqst-loder').hide();
                        $('.US_dealer-rslt').html("Login limit reached in acumatica");
                        console.log(error);
                    }
                });
            }
        });
});