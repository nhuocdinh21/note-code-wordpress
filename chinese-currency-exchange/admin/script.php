<?php
if  ( ! function_exists( 'add_script_to_admin_page' ) ){
	function add_script_to_admin_page()
	{
		global $pagenow;

		if( in_array( $pagenow, array('admin.php') ) && ( $_GET['page'] == 'currency-exchange.php' ) ) {
			// loading list css

		    // loading dataTables css
		    wp_enqueue_style( 'dataTables-css', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css', false, '1.0.0' );

		    // loading dataTables button css    
		    wp_enqueue_style( 'dataTables-button-css', 'https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css', false, '1.0.0' );

		    // loading fancybox css    
		    wp_enqueue_style( 'fancybox-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', false, '1.0.0' );

		    // loading currency-exchange css    
		    wp_enqueue_style( 'currency-exchange-css', plugin_dir_url( __FILE__ ) . 'css/currency-exchange.css', false, '1.0.0' );

		    // loading list js

		    // loading dataTables js    
		    wp_enqueue_script( 'dataTables-js', 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js', array('jquery'), false, true );

		    // loading button html5 js    
		    wp_enqueue_script( 'buttons-html5-js', 'https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js', array('jquery'), false, true );

		    // loading dataTables Button js    
		    wp_enqueue_script( 'dataTables-button-js', 'https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js', array('jquery'), false, true );

		    // loading jszip js    
		    wp_enqueue_script( 'jszip-js', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', array('jquery'), false, true );

		    // loading fancybox	    
		    wp_enqueue_script( 'fancybox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array('jquery'), false, true );

		    // add validation		    
		    wp_enqueue_script( 'validate-js', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js', array('jquery'), false, true );

		    // loading tab js    
		    wp_enqueue_script( 'tab-js', plugin_dir_url( __FILE__ ) . 'js/tab.js', array('jquery'), false, true );

		    // loading currency-exchange js    
		    wp_enqueue_script( 'currency-exchange-js', plugin_dir_url( __FILE__ ) . 'js/currency-exchange.js', array('jquery'), false, true );

			wp_localize_script( 'currency-exchange-js', 'currency_exchange_params', array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				// 'nonce'    => wp_create_nonce( 'currency-exchange-nonce' ),
			) );
			wp_enqueue_script('currency-exchange-js');
		}

		if( in_array( $pagenow, array('admin.php') ) && ( $_GET['page'] == 'currency-exchange-rate-form' ) ) {
			// loading chart currency exchange css    
		    wp_enqueue_style( 'form-currency-exchange-css', plugin_dir_url( __FILE__ ) . 'css/form-currency-exchange.css', false, '1.0.0' );
		}

		if( in_array( $pagenow, array('admin.php') ) && ( $_GET['page'] == 'chart-currency-exchange' ) ) {
			// loading chart currency exchange css    
		    wp_enqueue_style( 'chart-currency-exchange-css', plugin_dir_url( __FILE__ ) . 'css/chart-currency-exchange.css', false, '1.0.0' );
		}	
	}

	add_action( 'admin_enqueue_scripts', 'add_script_to_admin_page' );
}