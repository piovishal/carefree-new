jQuery(document).ready(function ($) {
        $('#ajax-rqst-loder').show();
        $.ajax({
            type: 'GET',
            url: MyAjax.ajaxurl,
            data: {
                action: 'CA_dealer_locator',
            },
            success: function(response) {
                console.log(response);
                $('#ajax-rqst-loder').hide();
                $('.CA_dealer-rslt').html(response.data.CA_rslt_data);
            },
            error: function(error) {
                console.log("error "+ error);
                $('#ajax-rqst-loder').hide();
                $('.CA_dealer-rslt').html("Login limit reached in acumatica");
                console.log(error);
            }
        });
    $('#ca-dealer').on('click', function(e) {
        var CA_state = jQuery('.CA-state').val();
        /* if (CA_state== "" ){
            alert("Please provide criteria to fetch results");
        }else{ */
            $('#ajax-rqst-loder').show();
            $.ajax({
                type: 'GET',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'CA_dealer_locator',
                    CA_state: CA_state,
                },
                success: function(response) {
                    console.log(response);
                    $('#ajax-rqst-loder').hide();
                    $('.CA_dealer-rslt').html(response.data.CA_rslt_data);
                },
                error: function(error) {
                    console.log("error "+ error);
                    $('#ajax-rqst-loder').hide();
                    $('.CA_dealer-rslt').html("Login limit reached in acumatica");
                    console.log(error);
                }
            });
            
        /* } */
    });
});