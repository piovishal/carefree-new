<?php namespace MeowCrew\RoleAndCustomerBasedPricing\Integrations;

use MeowCrew\RoleAndCustomerBasedPricing\Integrations\Plugins\WooCommerceProductAddons;

class Integrations {

	public function __construct() {
		$this->init();
	}

	public function init() {

		$plugins = apply_filters( 'tiered_pricing_table/integrations/plugins', array(
			WooCommerceProductAddons::class,
		) );

		foreach ( $plugins as $plugin ) {
			new $plugin();
		}
	}
}