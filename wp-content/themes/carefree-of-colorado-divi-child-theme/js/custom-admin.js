TODO:

jQuery(document).ready(function ($) {

        $('.O-view-distributor').show();
        $('.order-disrtibuotr-drpdwn').hide();
        $('.edit_address').on('click', function(e) {
          e.preventDefault(); // Prevent default link behavior
  
          // Toggle the visibility of the dropdown
          $('.order-disrtibuotr-drpdwn').show();
          $('.O-view-distributor').hide();
      });
      $('.save_order').on('click', function(e) {
          var order_id = $('#post_ID').val();
          var distributor_id = $('#O-selected-distributor').val();

          $.ajax({
              type: 'POST',
              url: MyAjax.ajaxurl,
              data: {
                  action: 'update_distributor',
                  order_id: order_id,
                  distributor_id: distributor_id,
              },
              success: function(response) {
                  // Handle success, e.g., show a success message
                  alert(response.message)
              }
          });
      });






});
