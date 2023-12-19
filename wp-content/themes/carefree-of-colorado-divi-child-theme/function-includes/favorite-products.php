<?php 
 /* Save purchased product details to custom table */
 function save_purchased_product_details($order_id){
    global $wpdb;
    $order = wc_get_order($order_id);
    $order_items = $order->get_items();
    $favorite_table_name = $wpdb->prefix.'favorite_product_list';
    foreach ($order_items as $item_id => $item_data) {
            $product_id = $item_data->get_product_id();
            $product = wc_get_product($product_id);
            $part_number = $product->get_sku();
            $product_name = $item_data['name'];
            $variation_product_id = $item_data['variation_id'];
            /* check if already product exist or not if exist then it will update else it is inserted */
            $existing_product = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM  $favorite_table_name WHERE product_id = %d", $product_id)
            );
            $favProduct = array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'part_number' => $part_number, 
            );
            if ($variation_product_id != 0) {
               $favProduct['product_varient_id'] = $variation_product_id;
           }
            if ($existing_product) {
                /* If the product exists, update the row */
                $wpdb->update(
                    $favorite_table_name,
                    $favProduct,
                    array('product_id' => $product_id),
                );
            } else {
                /* If the product does not exist, insert a new row */
                $wpdb->insert(
                    $favorite_table_name,
                    $favProduct,
                );
            }
    }
}
add_action('woocommerce_thankyou', 'save_purchased_product_details',111,1);


function favorite_product_table_shortcode()
{
    global $wpdb;

    /* Fetch all data from the custom table */
    $favorite_products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}favorite_product_list");
    if(empty($favorite_products)){
       
       $output = '<div class="msgWrapper graySection">
                     <p>You dont have any product in this list.</p>
                  </div>';
       return $output;
    }
        /*  Generate the table HTML */
        $output ='<div class="alert fav-sucess-msg alert-success" role="alert"></div>';
        $output .= '<table border="1" class="favorite-product-table">';
        $output .= '<tr>';
        $output .= '<th>Quantity</th>';
        $output .= '<th>Part Number</th>';
        $output .= '<th>Description</th>';
        $output .= '<th>Action</th>';
        $output .= '</tr>';
        foreach ($favorite_products as $product) {
            $output .= '<tr>';
            $output .= '<td><input type="number" name="quantity[]" value="1" min="1">';
            if($product->product_varient_id ==0){
                $output.=do_shortcode('[add_to_cart id="'.$product->product_id.'" quantity="1" show_price="FALSE"]');
            }else{
                   $product_detail = wc_get_product($product->product_id);
                   $slug = $product_detail->get_slug();
                   $output.=do_shortcode('[add_to_cart id="'.$product->product_varient_id.'" quantity="1" show_price="FALSE"]');
             }
            $output .= '</td>';
            $output .= '<td>' . $product->part_number . '</td>';
            $output .= '<td>' . $product->product_name .  '</td>';
            $output .= '<td><button class="remove-from-fav_list" data-product-id="' . esc_attr($product->product_id) . '">REMOVE FROM LIST</button></td>';
            $output .= '</tr>';
        }
    
        $output .= '</table>';
        wc_enqueue_js( "
          jQuery('input[name=\"quantity[]\"]').on('input', function() {
              var qty = jQuery(this).val();
              var atc = jQuery(this).closest('.favorite-product-table').find('.add_to_cart_button');
              
              if (atc.length > 0) {
                  atc.attr('data-quantity', qty);
              }
          });
      " );
        /* Return the generated table HTML */
        return $output;

}
add_shortcode('favorite_product_table', 'favorite_product_table_shortcode');


 /* 
 * AJAX callback to remove product from the list 
 */
add_action('wp_ajax_remove_fav_product_from_list', 'remove_fav_product_from_list');
add_action('wp_ajax_nopriv_remove_fav_product_from_list', 'remove_fav_product_from_list');
function remove_fav_product_from_list(){
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'favorite_product_list';
         /* Delete the product from the custom table */
        $wpdb->delete($table_name, array('product_id' => $product_id));
         /* Return a success response */
        wp_send_json_success('Product removed from the list successfully.');
    } else {
         /* Return an error response */
        wp_send_json_error('Invalid product ID.');
    }
}
