<?php
/* Add Table Currency */
if  ( ! function_exists( 'create_table_currency' ) ){
	function create_table_currency() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'alipay_currency';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate; ";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

/* insert initial value for table currency */
if  ( ! function_exists( 'insert_initial_currency' ) ){
	function insert_initial_currency() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'alipay_currency';

		$results = $wpdb->get_results("SELECT * FROM $table_name");

		if( empty($results) ){
			$wpdb->insert( 
				$table_name, 
				array( 
					'name' => 'Alipay Việt', 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'name' => 'Alipay Trung', 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'name' => 'Tệ thẻ', 
				) 
			);
		}
	}
}

/* delete table currency */
if  ( ! function_exists( 'delete_table_currency' ) ){
	function delete_table_currency()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'alipay_currency';
		$sql = "DROP TABLE IF EXISTS $table_name;";
	 	$wpdb->query($sql);
	}
}

/* Add Table Currency Exchange */
if  ( ! function_exists( 'create_table_currency_exchange' ) ){
	function create_table_currency_exchange() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'alipay';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			type text DEFAULT 'sell' NOT NULL,
			chinese_10am bigint(20) NOT NULL DEFAULT 0,
			chinese_16pm bigint(20) NOT NULL DEFAULT 0,	
			vietnamese_10am bigint(20) NOT NULL DEFAULT 0,
			vietnamese_16pm bigint(20) NOT NULL DEFAULT 0,	
			card_10am bigint(20) NOT NULL DEFAULT 0,
			card_16pm bigint(20) NOT NULL DEFAULT 0,		
			PRIMARY KEY (id)
		) $charset_collate; ";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

/* insert initial value for table currency */
if  ( ! function_exists( 'insert_initial_currency_exchange' ) ){
	function insert_initial_currency_exchange() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'alipay';

		$results = $wpdb->get_results("SELECT * FROM $table_name");

		if( empty($results) ){
			$wpdb->insert( 
				$table_name, 
				array( 
					'time'                  => '2022-10-20', 				 
					'type'                  => 'sell', 				 
					'chinese_10am'          => 3000, 				 
					'chinese_16pm'          => 3100, 
					'vietnamese_10am'       => 3200, 				 
					'vietnamese_16pm'       => 3150, 
					'card_10am'             => 3200, 				 
					'card_16pm'             => 3250, 				 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'time'                  => '2022-10-21', 				 
					'type'                  => 'sell', 				 
					'chinese_10am'          => 3200, 				 
					'chinese_16pm'          => 3150, 
					'vietnamese_10am'       => 3300, 				 
					'vietnamese_16pm'       => 3350, 
					'card_10am'             => 3250, 				 
					'card_16pm'             => 3300, 				 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'time'                  => current_time( 'mysql' ), 				 
					'type'                  => 'sell', 				 
					'chinese_10am'          => 3300, 				 
					'chinese_16pm'          => 3250, 
					'vietnamese_10am'       => 3200, 				 
					'vietnamese_16pm'       => 3150, 
					'card_10am'             => 3300, 				 
					'card_16pm'             => 3250, 				 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'time'                  => '2022-10-20', 				 
					'type'                  => 'buy', 				 
					'chinese_10am'          => 3100, 				 
					'chinese_16pm'          => 3150, 
					'vietnamese_10am'       => 3250, 				 
					'vietnamese_16pm'       => 3200, 
					'card_10am'             => 3300, 				 
					'card_16pm'             => 3350, 				 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'time'                  => '2022-10-21', 				 
					'type'                  => 'buy', 				 
					'chinese_10am'          => 3300, 				 
					'chinese_16pm'          => 3250, 
					'vietnamese_10am'       => 3300, 				 
					'vietnamese_16pm'       => 3250, 
					'card_10am'             => 3250, 				 
					'card_16pm'             => 3200, 				 
				) 
			);

			$wpdb->insert( 
				$table_name, 
				array( 
					'time'                  => current_time( 'mysql' ), 				 
					'type'                  => 'buy', 				 
					'chinese_10am'          => 3200, 				 
					'chinese_16pm'          => 3150, 
					'vietnamese_10am'       => 3300, 				 
					'vietnamese_16pm'       => 3350, 
					'card_10am'             => 3300, 				 
					'card_16pm'             => 3350, 				 
				) 
			);
		}
	}
}

/* delete table currency exchange */
if  ( ! function_exists( 'delete_table_currency_exchange' ) ){
	function delete_table_currency_exchange()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'alipay';
		$sql = "DROP TABLE IF EXISTS $table_name;";
	 	$wpdb->query($sql);
	}
}