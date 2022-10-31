<?php
if  ( ! function_exists( 'add_script_to_frontend_page' ) ){
	function add_script_to_frontend_page()
	{
		// add frontend currency exchange css
		wp_enqueue_style( 'view_currency_exchange_css', plugin_dir_url( __FILE__ ) . 'css/view_currency_exchange.css' );

		// add chart js
		wp_enqueue_script( 'chart_js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js', array('jquery'));

		// add frontend currency exchange js
		wp_enqueue_script( 'view_currency_exchange_js', plugin_dir_url( __FILE__ ) . 'js/view_currency_exchange.js', array('jquery'));
	}
	add_action( 'wp_enqueue_scripts', 'add_script_to_frontend_page' );
}