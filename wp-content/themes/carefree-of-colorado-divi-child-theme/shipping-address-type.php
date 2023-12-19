<?php
// Get the OptionTree object
$optiontree = get_option('option_tree');
$residential_additional_fees = $optiontree['additional_fees_for_residential_address'];
$commercial_additional_fees = $optiontree['additional_fees_for_commercial_address'];

add_action( 'woocommerce_form_field_radio', 'custom_form_field_radio', 20, 4 );
function custom_form_field_radio( $field, $key, $args, $value ) {
    if ( ! empty( $args['options'] ) && is_checkout() ) {
		
		$field = str_replace( '</label><input ', '</label><br><input ', $field );
        $field = str_replace( '<label ', '<label style="display:inline;margin-left:8px;" ', $field );
    }
    return $field;
}

function shippingClassExists()
{
	$shipping_class = array(
        'ltl-large', 'ltl-medium', 'ltl-small'
    );
	
	$shipping_class_exists = false;
    foreach(WC()->cart->get_cart_contents() as $key => $values) {
        if ( in_array($values['data']->get_shipping_class() , $shipping_class) ) {
            $shipping_class_exists = true;
            break;
        }
    }
	return $shipping_class_exists;
}

// Add a custom dynamic packaging fee
add_action( 'woocommerce_cart_calculate_fees', 'add_packaging_fee', 20, 1 );
function add_packaging_fee( $cart ) {
    global $residential_additional_fees;
    global $commercial_additional_fees;
    if (shippingClassExists() && is_checkout()) {
		
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
			return;

		$packing_fee = WC()->session->get( 'address_type' ); // Dynamic packing fee
		$fee = $packing_fee === 'residential' ?  $residential_additional_fees : $commercial_additional_fees;
        // prd($fee);
        if($packing_fee === 'residential'){
            $packing_fee_adrs = 'Residential Address';
        }else{
            $packing_fee_adrs = 'Commercial Address';
        }
		$cart->add_fee( __( 'Delivery to '.$packing_fee_adrs, 'woocommerce' ), $fee );
	}	
}

// Add a custom radio fields for packaging selection
add_action( 'woocommerce_review_order_after_shipping', 'checkout_shipping_form_packing_addition', 20 );
function checkout_shipping_form_packing_addition() {
	global $residential_additional_fees;
	global $commercial_additional_fees;
	
	
	if (shippingClassExists() && is_checkout()) {
		
		$domain = 'woocommerce';

		echo '<tr class="packing-select"><th colspan="2">' . __('Address Type', $domain) . '</th><td>';

		$chosen   = WC()->session->get('address_type');
		$chosen   = empty($chosen) ? WC()->checkout->get_value('address_type') : $chosen;
		$chosen   = empty($chosen) ? 'bag' : $chosen;

		// Add a custom checkbox field
		woocommerce_form_field( 'address_type', array(
			'type' => 'radio',
			'class' => array( 'form-row-wide packing' ),
			'options' => array(
				'commercial' => 'Commercial Address (additional ' .  get_woocommerce_currency_symbol() . $commercial_additional_fees . ')',
				'residential' => 'Residential Address (additional ' .  get_woocommerce_currency_symbol() . $residential_additional_fees . ')',
			),
			'default' => 'commercial',
		), $chosen );

		echo '</td></tr>';
	}	
}

// jQuery - Ajax script
add_action( 'wp_footer', 'checkout_shipping_packing_script' );
function checkout_shipping_packing_script() {
    if ( ! is_checkout() )
        return; // Only checkout page
    ?>
    <script type="text/javascript">
    jQuery( function($){
		jQuery('form').on('change', 'input[name=address_type]', function(e){
            e.preventDefault();
            var p = $(this).val();
            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    'action': 'woo_get_ajax_data',
                    'packing': p,
                },
                success: function (result) {
                    $('body').trigger('update_checkout');
                    console.log('response: '+result); // just for testing | TO BE REMOVED
                },
                error: function(error){
                    console.log(error); // just for testing | TO BE REMOVED
                }
            });
        });
    });
    </script>
    <?php

}

// Php Ajax (Receiving request and saving to WC session)
add_action( 'wp_ajax_woo_get_ajax_data', 'woo_get_ajax_data' );
add_action( 'wp_ajax_nopriv_woo_get_ajax_data', 'woo_get_ajax_data' );
function woo_get_ajax_data() {
    if ( isset($_POST['packing']) ){
        $packing = sanitize_key( $_POST['packing'] );
        WC()->session->set('address_type', $packing );
        echo json_encode( $packing );
    }
    die(); // Alway at the end (to avoid server error 500)
}