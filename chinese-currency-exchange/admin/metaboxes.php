<?php
add_action( 'cmb2_admin_init', 'phongmy_cmb2_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function phongmy_cmb2_sample_metaboxes() {

	$frm_exchange_prefix = 'frm_exchange_';

	$frm_exchange = new_cmb2_box( array(
		'id'            => 'form_exchange_setting',
		'title'         => __( 'Biểu mẫu tỷ giá hối đoái tiền tệ', 'phongmy' ),
		'object_types'  => array( 'options-page', ), // Post type
		'option_key'    => 'currency-exchange-rate-form',
		'menu_title'    => __( 'Form', 'phongmy' ),
		'parent_slug'   => 'currency-exchange.php',
		'capability'    => 'manage_options',
	) );

	// add shortcode use
	$frm_exchange->add_field( array(
	    'name' => __('Hiển thị biểu mẫu', 'phongmy'),
	    'desc' => __('Sử dụng Shortcode [form_currency_exchange] để hiển thị Biểu mẫu.', 'phongmy'),
	    'type' => 'title',
	    'id'   => $frm_exchange_prefix . 'shortcode',
	) );

	// add Primary Color
	$frm_exchange->add_field( array(
	    'name'    => __('Màu chủ đạo 1'),
	    'id'      => $frm_exchange_prefix . 'primary_color',
	    'type'    => 'colorpicker',
	    'default' => '#6440fb',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add Secondary Color
	$frm_exchange->add_field( array(
	    'name'    => __('Màu chủ đạo 2'),
	    'id'      => $frm_exchange_prefix . 'secondary_color',
	    'type'    => 'colorpicker',
	    'default' => '#EB5757',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add Third Color
	$frm_exchange->add_field( array(
	    'name'    => __('Màu chủ đạo 3'),
	    'id'      => $frm_exchange_prefix . 'third_color',
	    'type'    => 'colorpicker',
	    'default' => '#0DFC89',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add Background Color Name Currency
	$frm_exchange->add_field( array(
	    'name'    => __('Màu nền tên tiền tệ'),
	    'id'      => $frm_exchange_prefix . 'currency_name_background',
	    'type'    => 'colorpicker',
	    'default' => '#E8FFF4',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add Description Form Color
	$frm_exchange->add_field( array(
	    'name'    => __('Màu nền mô tả biểu mẫu'),
	    'id'      => $frm_exchange_prefix . 'description_background',
	    'type'    => 'colorpicker',
	    'default' => '#fdeeee',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add title form
	$frm_exchange->add_field( array(
	    'name'    => __('Tiêu đề','phongmy'),
	    'default' => __('Tỷ giá tệ hôm nay','phongmy'),
	    'id'      => $frm_exchange_prefix . 'title',
	    'type'    => 'text',
	) );

	// add tag title form
	$frm_exchange->add_field( array(
	    'name'             => __('Thẻ tiêu đề','phongmy'),
	    'id'               => $frm_exchange_prefix . 'tag_title',
	    'type'             => 'select',
	    'show_option_none' => true,
	    'default'          => 'h2',
	    'options'          => array(
	        'h1'       => 'H1',	        
	        'h2'       => 'H2',	        
	        'h3'       => 'H3',	        
	        'h4'       => 'H4',	        
	        'h5'       => 'H5',	        
	        'h6'       => 'H6',	        
	        'div'      => 'DIV',	        
	    ),
	) );

	// add Currency Title
	$frm_exchange->add_field( array(
	    'name'    => __('Tiêu đề Tiền tệ 1','phongmy'),
	    'default' => __('Alipay Trung','phongmy'),
	    'id'      => $frm_exchange_prefix . 'title_currency_1',
	    'type'    => 'text',
	) );

	$frm_exchange->add_field( array(
	    'name'    => __('Tiêu đề Tiền tệ 2','phongmy'),
	    'default' => __('Alipay Việt','phongmy'),
	    'id'      => $frm_exchange_prefix . 'title_currency_2',
	    'type'    => 'text',
	) );

	$frm_exchange->add_field( array(
	    'name'    => __('Tiêu đề Tiền tệ 3','phongmy'),
	    'default' => __('Tệ thẻ','phongmy'),
	    'id'      => $frm_exchange_prefix . 'title_currency_3',
	    'type'    => 'text',
	) );

	// add service fee
	$frm_exchange->add_field( array(
	    'name' => __('Phí dịch vụ', 'phongmy'),
	    'id'   => $frm_exchange_prefix . 'service_fee',
	    'after_field' => 'đ',
	    'type' => 'text',
	    'default' => '0',
		'attributes' => array(
			'type' => 'number',
			'pattern' => '\d*',
		),
		'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',	
	) );

	// add service fee
	$frm_exchange->add_field( array(
	    'name' => __('Phí Thanh toán hộ', 'phongmy'),
	    'id'   => $frm_exchange_prefix . 'payment_service_fee',
	    'after_field' => 'đ',
	    'type' => 'text',
	    'default' => '0',
		'attributes' => array(
			'type' => 'number',
			'pattern' => '\d*',
		),
		'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',	
	) );

	// add description
	$frm_exchange->add_field( array(
	    'name'    => __('Mô tả', 'phongmy'),
	    'id'      => $frm_exchange_prefix . 'description',
	    'type'    => 'wysiwyg',
	    'options' => array(),
	) );

	// add metabox for Chart page
	$chart_prefix = 'chart_currency_exchange_';

	$chart = new_cmb2_box( array(
		'id'            => 'chart_currency_setting',
		'title'         => __( 'Biểu đồ tỷ giá', 'phongmy' ),
		'object_types'  => array( 'options-page', ), // Post type
		'option_key'    => 'chart-currency-exchange',
		'menu_title'    => __( 'Chart', 'phongmy' ),
		'parent_slug'   => 'currency-exchange.php',
		'capability'    => 'manage_options',
	) );

	// add shortcode use
	$chart->add_field( array(
	    'name' => __('Hiển thị biểu đồ', 'phongmy'),
	    'desc' => __('Sử dụng Shortcode [chart_currency_exchange] để hiển thị Biểu đồ tỷ giá.', 'phongmy'),
	    'type' => 'title',
	    'id'   => $chart_prefix . 'shortcode',
	) );

	// add Primary Color
	$chart->add_field( array(
	    'name'    => __('Màu chủ đạo 1'),
	    'id'      => $chart_prefix . 'primary_color',
	    'type'    => 'colorpicker',
	    'default' => '#6440fb',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add Secondary Color
	$chart->add_field( array(
	    'name'    => __('Màu chủ đạo 2'),
	    'id'      => $chart_prefix . 'secondary_color',
	    'type'    => 'colorpicker',
	    'default' => '#eb5757',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add chart title
	$chart->add_field( array(
	    'name'    => __('Tiêu đề biểu đồ','phongmy'),
	    'default' => __('Thống kê giá trị quy đổi tệ thẻ','phongmy'),
	    'id'      => $chart_prefix . 'title',
	    'type'    => 'text',
	) );

	// add tag title chart
	$chart->add_field( array(
	    'name'             => __('Thẻ tiêu đề','phongmy'),
	    'id'               => $chart_prefix . 'tag_title',
	    'type'             => 'select',
	    'show_option_none' => true,
	    'default'          => 'h2',
	    'options'          => array(
	        'h1'       => 'H1',	        
	        'h2'       => 'H2',	        
	        'h3'       => 'H3',	        
	        'h4'       => 'H4',	        
	        'h5'       => 'H5',	        
	        'h6'       => 'H6',	        
	        'div'      => 'DIV',	        
	    ),
	) );

	// add list days sell
	$chart->add_field( array(
	    'name'    => __('Ngày Bán ra', 'phongmy'),
	    'id'      => $chart_prefix . 'sell_days',
	    'type'    => 'multicheck',
	    'options_cb' => 'show_option_sell_days',
	) ); 

	// add list days buy
	$chart->add_field( array(
	    'name'    => __('Ngày Mua vào', 'phongmy'),
	    'id'      => $chart_prefix . 'buy_days',
	    'type'    => 'multicheck',
	    'options_cb' => 'show_option_buy_days',
	) ); 

	// add height chart
	$chart->add_field( array(
	    'name' => __('Chiều cao Biểu đồ', 'phongmy'),
	    'id'   => $chart_prefix . 'chart_height',
	    'after_field' => 'px',
	    'type' => 'text',
	    'default' => '500',
		'attributes' => array(
			'type' => 'number',
			'pattern' => '\d*',
		),
		'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',	
	) );

	// add color Alipay Chinese
	$chart->add_field( array(
	    'name'    => __('Màu viền Alipay Trung', 'phongmy'),
	    'id'      => $chart_prefix . 'alipay_chinese_border_color',
	    'type'    => 'colorpicker',
	    'default' => '#800080',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) ); 

	// add color Alipay Vietnamese
	$chart->add_field( array(
	    'name'    => __('Màu viền Alipay Việt', 'phongmy'),
	    'id'      => $chart_prefix . 'alipay_vietnamese_border_color',
	    'type'    => 'colorpicker',
	    'default' => '#0000ff',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );

	// add color Card
	$chart->add_field( array(
	    'name'    => __('Màu viền Tệ thẻ', 'phongmy'),
	    'id'      => $chart_prefix . 'card_border_color',
	    'type'    => 'colorpicker',
	    'default' => '#008000',
	    'options' => array(
	        'alpha' => true, // Make this a rgba color picker.
	    ),
	) );  
}

// Callback function
function show_option_sell_days( $field ) {
	global $wpdb; 
	$table_name = $wpdb->prefix . 'alipay';
	$list_currency_exchange_sell = $wpdb->get_results("SELECT * FROM $table_name WHERE type LIKE 'sell'");
	$list_sell_days = array();
	if( !empty($list_currency_exchange_sell) ){
		foreach( $list_currency_exchange_sell as $item ):
			$day_item = date('d/m/Y', strtotime($item->time));
			$list_sell_days[$item->id] = $day_item;
		endforeach;
	}
    return $list_sell_days;
}

// Callback function
function show_option_buy_days( $field ) {
	global $wpdb; 
	$table_name = $wpdb->prefix . 'alipay';
	$list_currency_exchange_buy = $wpdb->get_results("SELECT * FROM $table_name WHERE type LIKE 'buy'");
	$list_buy_days = array();
	if( !empty($list_currency_exchange_buy) ){
		foreach( $list_currency_exchange_buy as $item ):
			$day_item = date('d/m/Y', strtotime($item->time));
			$list_buy_days[$item->id] = $day_item;
		endforeach;
	}
    return $list_buy_days;
}