<?php


namespace PaymentPlugins\WooCommerce\Blocks\Braintree\Payments\Gateways;


class ApplePayGateway extends AbstractGateway {

	protected $name = 'braintree_applepay';

	public function get_payment_method_script_handles() {
		$this->assets_api->register_script( 'wc-braintree-blocks-applepay', 'build/wc-braintree-applepay.js' );

		return [ 'wc-braintree-blocks-applepay' ];
	}

	public function get_payment_method_data() {
		return parent::get_payment_method_data() + [
				'displayName'   => $this->get_setting( 'store_name' ),
				'buttonStyle'   => $this->get_setting( 'button' ),
				'buttonType'    => $this->get_setting( 'button_type_checkout' ),
				'roundedButton' => $this->get_setting( 'button_style', 'standard' ) === 'rounded',
				'routes'        => [
					'paymentMethod' => \WC_Braintree_Rest_API::get_endpoint( braintree()->rest_api->applepay->rest_uri() . '/payment-method' )
				]
			];
	}

}