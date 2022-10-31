<?php 
/**
	* Plugin name: Chinese Currency Exchange
	* Plugin URI: https://phongmy.vn/
	* Description: Chinese currency exchange plugin
	* Author: Phong My
	* Author URI: https://phongmy.vn/
	* text-domain: phongmy
**/

defined('ABSPATH') or die('Unauthorized Access');

// add file admin
require_once plugin_dir_path( __FILE__ ) . '/admin/database-manager.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/table-manager.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/script.php';
require_once plugin_dir_path( __FILE__ ) . '/addon/cmb2/init.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/metaboxes.php';

// add file for view frontend
require_once plugin_dir_path( __FILE__ ) . 'view/shortcodes.php';
require_once plugin_dir_path( __FILE__ ) . 'view/script.php';

// add initial functions when active plugin
if  ( ! function_exists( 'activation_initial_functions' ) ){
	function activation_initial_functions() {
		// create_table_currency();
		// insert_initial_currency();
		create_table_currency_exchange();
		insert_initial_currency_exchange();
	}
}

// remove initial functions when deactive plugin
if  ( ! function_exists( 'deactivation_initial_functions' ) ){
	function deactivation_initial_functions() {
		// delete_table_currency();
		// delete_table_currency_exchange();
	}
}

register_activation_hook( __FILE__, 'activation_initial_functions' );
register_deactivation_hook( __FILE__, 'deactivation_initial_functions' );

// add ajax add new data table
if  ( ! function_exists( 'addnew_data_table' ) ){
	function addnew_data_table(){
	    global $wpdb;

	    $type              = $_POST['type'];
	    $date              = $_POST['date'];
	    $timeline          = $_POST['timeline'];
	    $alipay_chinese    = intval( $_POST['alipay_chinese'] );
	    $alipay_vietnamese = intval( $_POST['alipay_vietnamese'] );
	    $card              = intval( $_POST['card'] );

	    $date_timeline = ( $timeline == '16pm' ) ? $date . ' 16:00:00' : $date . ' 10:00:00';

	    $table_name = $wpdb->prefix . 'alipay';

		$currency_exchange = $wpdb->get_row(" SELECT * FROM $table_name WHERE time LIKE '$date%' AND type LIKE '$type' ");

		if( !empty($currency_exchange) ) { 
			$action_status = $wpdb->update( 
				$table_name, 
				array( 
					'time'                    => $date_timeline,
					'type'                    => $currency_exchange->type,  				 
					'chinese_' . $timeline    => $alipay_chinese,  				 
					'vietnamese_' . $timeline => $alipay_vietnamese,  				 
					'card_' . $timeline       => $card,  				 
				),
				array(
					'id'   => $currency_exchange->id,					
				) 
			);			
		}
		else {
			$action_status = $wpdb->insert( 
				$table_name, 
				array( 
					'time'                     => $date_timeline, 				 
					'type'                     => $type, 				 
					'chinese_' . $timeline     => $alipay_chinese, 				 
					'vietnamese_' . $timeline  => $alipay_vietnamese, 				  
					'card_' . $timeline        => $card, 				  				 
				) 
			);
		}

		$status = ( false === $action_status ) ? false : true;

	    $response = array('status' => $status);

		die(json_encode($response));
	}

	add_action('wp_ajax_addnew_data_table', 'addnew_data_table');
	add_action('wp_ajax_nopriv_addnew_data_table', 'addnew_data_table');
}

// add ajax update data table
if  ( ! function_exists( 'update_data_table' ) ){
	function update_data_table(){
	    global $wpdb;

	    if ( $_POST['id'] ){
			$field_id    = intval( $_POST['id'] );
			$field_type  = $_POST['type'];
			$field_key   = $_POST['key'];
			$field_value = intval( $_POST['value'] );

			$table_name = $wpdb->prefix . 'alipay';

			$currency_exchange = $wpdb->get_row(" SELECT * FROM $table_name WHERE id = $field_id AND type = '$field_type' ");

			if( !empty($currency_exchange) ) {
				$updated = $wpdb->update( 
					$table_name, 
					array( 
						$field_key => $field_value,  				 
					),
					array(
						'id' => $field_id,
						'type' => $field_type,
					) 
				);

				if ( false === $updated ) {
					$update_status = false;
				}
				else {
					$update_status = true;
				}
				
			}
			else {
				$update_status = false;
			}

			$response = array('update_status' => $update_status);

			die(json_encode($response));
	    }
	}

	add_action('wp_ajax_update_data_table', 'update_data_table');
	add_action('wp_ajax_nopriv_update_data_table', 'update_data_table');
}

// add ajax delete data row
if  ( ! function_exists( 'delete_data_row' ) ){
	function delete_data_row(){
	    global $wpdb;

	    if ( $_POST['id'] ){
			$field_id    = intval( $_POST['id'] );
			$field_type  = $_POST['type'];

			$table_name = $wpdb->prefix . 'alipay';

			$currency_exchange = $wpdb->get_row(" SELECT * FROM $table_name WHERE id = $field_id AND type = '$field_type' ");

			if( !empty($currency_exchange) ) {
				$deleted = $wpdb->delete(
			        $table_name,
			        array(
			        	'id' => $field_id,
			        	'type' => $field_type,
			        ),
			        array(
			        	'%d',
			        	'%s'
			        )
			    );

				if ( false === $deleted ) {
					$delete_status = false;
				}
				else {
					$delete_status = true;
				}
				
			}
			else {
				$delete_status = false;
			}

			$response = array('delete_status' => $delete_status);

			die(json_encode($response));
	    }
	}

	add_action('wp_ajax_delete_data_row', 'delete_data_row');
	add_action('wp_ajax_nopriv_delete_data_row', 'delete_data_row');
}

// define function get value metaboxes
function get_option_chart( $key = '', $default = false ) {
    if ( function_exists( 'cmb2_get_option' ) ) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option( 'chart-currency-exchange', $key, $default );
    }
    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option( 'chart-currency-exchange', $default );
    $val = $default;
    if ( 'all' == $key ) {
        $val = $opts;
    } elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
        $val = $opts[ $key ];
    }
    return $val;
}

function get_option_form( $key = '', $default = false ) {
    if ( function_exists( 'cmb2_get_option' ) ) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option( 'currency-exchange-rate-form', $key, $default );
    }
    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option( 'currency-exchange-rate-form', $default );
    $val = $default;
    if ( 'all' == $key ) {
        $val = $opts;
    } elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
        $val = $opts[ $key ];
    }
    return $val;
}