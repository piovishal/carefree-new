<?php
/**
 * @version 3.0.0
 * @package Braintree/Templates
 */
?>
<span class="wc-braintree-card-icons-container">
<?php foreach ( $payment_methods as $method ): ?>
	<?php printf( '<img class="wc-braintree-card-icon ' . esc_attr( $type ) . ' ' . esc_attr( $method ) . '" src="%1$s%2$s%3$s%4$s"/>', esc_url( braintree()->assets_path() ), 'img/payment-methods/' . esc_attr( $type ) . '/', esc_attr( $method ), '.svg' ) ?>
<?php endforeach; ?>
</span>