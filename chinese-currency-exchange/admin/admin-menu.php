<?php
/**
 * Register a custom menu page.
 */
if  ( ! function_exists( 'wpdocs_register_my_custom_menu_page' ) ){
	function wpdocs_register_my_custom_menu_page(){
		add_menu_page( 
			__( 'Currency Exchange Setting', 'phongmy' ),
			__( 'Currency Exchange', 'phongmy' ),
			'manage_options',
			'currency-exchange.php',
			'show_currency_exchange_setting',
			'dashicons-money-alt',
			85
		); 
	}
	add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );
}