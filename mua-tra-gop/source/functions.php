<?php
/**
 * Plugin Name: Vinahost Installment Purchase
 * Plugin URI: https://vinahost.vn/
 * Description: Tính phí và đăng ký mua trả góp cho Woocommerce 
 * Version: 1.0
 * Author: Vinahost - VNHTeam
 * Author URI: https://vinahost.vn/
 * License: GPLv2
 */

if( ! defined( 'ABSPATH' ) ) exit;

define('PLUGIN_URL', plugin_dir_url(__FILE__));
define('PLUGIN_PATH', plugin_dir_path(__FILE__));

if ( !class_exists('acf') ) {

	add_filter('acf/settings/path', 'cysp_acf_settings_path');
	function cysp_acf_settings_path( $path ) {
		$path = plugin_dir_path( __FILE__ ) . '/includes/acf/';
		return $path;
	}
  
	add_filter('acf/settings/dir', 'cysp_acf_settings_dir');
	function cysp_acf_settings_dir( $path ) {
		$dir = plugin_dir_url( __FILE__ ) . '/includes/acf/';
		return $dir;
	}
  
  	// add_filter('acf/settings/show_admin', '__return_false');
  	include_once( plugin_dir_path( __FILE__ ) . '/includes/acf/acf.php' );

  	add_filter('acf/settings/save_json', 'cysp_acf_json_save_point');
  	function cysp_acf_json_save_point( $path ) {
    	$path = plugin_dir_path( __FILE__ ) . 'acf-json/';
    	return $path;
  	}

  	add_filter('acf/settings/load_json', 'cysp_acf_json_load_point');
  	function cysp_acf_json_load_point( $paths ) {
	  	$paths[] = plugin_dir_path( __FILE__ ) . 'acf-json-load';
	  	return $paths;
	}

  	add_filter( 'site_transient_update_plugins', 'cysp_stop_acf_update_notifications', 11 );
  	function cysp_stop_acf_update_notifications( $value ) {
    	unset( $value->response[ plugin_dir_path( __FILE__ ) . '/includes/acf/acf.php' ] );
    	return $value;
  	}
  
} 
else 
{
	add_filter('acf/settings/load_json', 'cysp_acf_json_load_point');
}

//add admin css
function tragop_admin() {
	wp_enqueue_style('tragop', plugins_url('/css/admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'tragop_admin');

// add frontend css
function style_frontend() {
    wp_register_style('frontend_css', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style('frontend_css');

    wp_register_script('validate', plugins_url( '/js/jquery.validate.min.js', __FILE__ ));
    wp_enqueue_script( 'validate' );
}
add_action('wp_print_scripts', 'style_frontend');

// add post type list Installment Purchase 
add_action( 'init', 'list_customer_register' ); 
function list_customer_register() {   

    $labels = array( 
        'name' => __('Danh sách mua trả góp', 'custom'), 
        'add_new' => __('Thêm mới', 'custom'), 
        'add_new_item' => __('Thêm mới','custom'), 
        'edit_item' => __('Sửa thông tin trả góp','custom'), 
        'new_item' => __('Thêm mới','custom'), 

        'view_item' => __('Xem thông tin trả góp','custom'), 
        'search_items' => __('Tìm kiếm trả góp','custom'), 
        'not_found' => __('Không tin thấy','custom'), 
        'not_found_in_trash' => __('Không có gì trong Thùng rác','custom'), 
        'parent_item_colon' => '' 
    );   

    $args = array( 
        'labels' => $labels, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true, 
        'menu_icon' => 'dashicons-clipboard', 
        'rewrite' => array( 'slug' => 'tragop', 'with_front'=> false ), 
        'capability_type' => 'post',
        'hierarchical' => true,
        'has_archive' => false,  
        'menu_position' => null, 
        'supports' => array('title'),
    );
    register_post_type( 'tragop' , $args );
}

// add meta box save customer information
function add_purchase_info_meta_boxes() {
	add_meta_box("purchase_meta_box", __('Thông tin trả góp','custom'), "add_info_customer_meta_box", "tragop", "normal", "high");
}
function add_info_customer_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
	?>
		<div class="row customer_info">
			<div class="col large-6 small-12 info_purchase admin_style">
				<div class="col-inner">
					<h3 class="title"><?php echo __('Thông tin trả góp','custom'); ?></h3>
					<p><b><?php echo __('Sản phẩm','custom'); ?>: </b><input type="text" readonly name="tensp" value="<?php echo @$custom["tensp"][0]; ?>"></p>
					<p><b><?php echo __('Tổng giá trị sản phẩm','custom'); ?>: </b><input type="text" readonly name="giaban" value="<?php echo @$custom["giaban"][0]; ?>"></p>
					<div class="space"></div>
					<p><b><?php echo __('Thanh toán trước','custom'); ?>: </b><input type="text" readonly name="thanhtoan" value="<?php echo @$custom["thanhtoan"][0]; ?>"></p>
					<p><b><?php echo __('Thời gian thanh toán','custom'); ?>: </b><input type="text" readonly name="thoigian" value="<?php echo @$custom["thoigian"][0]; ?>"></p>
					<p><b><?php echo __('Lãi suất','custom'); ?>: </b><input type="text" readonly name="laisuat" value="<?php echo @$custom["laisuat"][0]; ?>"></p>
					<p><b><?php echo __('Ngân hàng','custom'); ?>: </b><input type="text" readonly name="nganhang" value="<?php echo @$custom["nganhang"][0]; ?>"></p>
					<div class="space"></div>
					<p><b><?php echo __('Số tiền thanh toán trước','custom'); ?>: </b><input type="text" readonly name="tientt" value="<?php echo @$custom["tientt"][0]; ?>"></p>
					<p><b><?php echo __('Số tiền đóng hàng tháng','custom'); ?>: </b><input type="text" readonly name="tienht" value="<?php echo @$custom["tienht"][0]; ?>"></p>
					<p><b><?php echo __('Ngày bắt đầu thanh toán','custom'); ?>: </b><input type="text" readonly name="date_publish" value="<?php echo get_the_date(); ?>"></p>
					<p><input type="hidden" name="month" value="<?php echo @$custom["month"][0]; ?>"></p>
					<p><input type="hidden" name="date_publish" value="<?php echo get_the_date(); ?>"></p>
					<p><input type="hidden" name="date_purchase" value="<?php echo @$custom["date_purchase"][0]; ?>"></p>

					<?php 
						$args = array(
						    'posts_per_page' => -1,
						    'post_type' => 'tragop',
						    'meta_query' => array(
						        array(
						            'key'     => 'status',
						            'value'   => '0',
						            'compare' => '=',
						        ),
						    ),
						); 
						$ids = array();
						$query = new WP_Query( $args );
						if ( $query->have_posts() ) :
							while ( $query->have_posts() ) : $query->the_post();
								$date_publish = get_the_date('m/d/Y');
								$date_current = date('m/d/Y');

								$date_diff = $date_current - $date_publish;

								$v_current = strtotime($date_current);
								$v_publish = strtotime($date_publish);

								// check month current with month publish
								$year1 = date('Y', $v_publish);
								$year2 = date('Y', $v_current);

								$month1 = date('m', $v_publish);
								$month2 = date('m', $v_current);

								$day1 = date('d', $v_publish);
								$day2 = date('d', $v_current);

								// if( $date_diff == 1 ):
								// 	$diff = '0';
								// else:
								// 	$diff = (($year2 - $year1) * 12) + ($month2 - $month1) - 1;
								// endif;

								$diff = (($year2 - $year1) * 12) + ($month2 - $month1) - 1;

								echo $diff;

								// set date expire
								$month = get_post_meta( get_the_ID(), 'month', true );
								$date_3 = get_the_date('m/d/Y');
								$date_expire = strtotime($date_3 . "+".$month." month");
								$expire = date('m/d/Y', $date_expire);
								$v_expire = strtotime($expire);

								if( $v_current <= $v_expire ):
									$d_current = date('d', $v_current);
									$d_publish = date('d', $v_publish);
									$day_temp = strtotime($date_publish . "+20 days +".$diff." month");
									$d_temp = date('d', $day_temp);
									echo date('d', $day_temp).'/'.date('m', $day_temp).'/'.date('Y', $day_temp);
									if( $d_current == $d_temp ):
										array_push($ids, get_the_ID());
									endif;								
								endif;
							endwhile;
						endif;
						wp_reset_query();
						// var_dump($ids);
						$ids = implode(',', $ids);
						if ( !empty( $ids ) ):
							$ids = explode( ',', $ids );
							$ids = array_map( 'trim', $ids );
							$args2 = array(
								'post__in' => $ids,
						        'post_type' => 'tragop',
								'numberposts' => -1,
								'orderby' => 'post__in',
								'posts_per_page' => 9999,
								'ignore_sticky_posts' => true,
							);
							$query2 = new WP_Query( $args2 );
							if ( $query2->have_posts() ) :
								while ( $query2->have_posts() ) : $query2->the_post();
									// $to = get_post_meta( get_the_ID(), 'email', true );
									// if( get_field('title_another','option') ):
									// 	$subject = get_field('title_another','option');
									// else:
									// 	$subject = __('Thông báo nhắc nhở thanh toán vay trả góp cho sản phẩm ','custom').get_post_meta( get_the_ID(), 'tensp', true ).'__'.get_post_meta( get_the_ID(), 'dienthoai', true );
									// endif;
									// if( get_field('content_another','option') ):
									// 	$body = get_field('content_another','option');
									// else:
									// 	$body = __('Chào bạn. Bạn đã đến hạn thanh toán vay trả góp. Vui lòng thực hiện thanh toán đúng hạn.','custom');
									// endif;
									// $headers = array('Content-Type: text/html; charset=UTF-8');
									 
									// wp_mail( $to, $subject, $body, $headers );
								endwhile;
							endif;
							wp_reset_query(); 
						endif;
					?>
				</div>
			</div>
			<div class="col large-6 small-12 info_customer admin_style">
				<div class="col-inner">
					<h3 class="title"><?php echo __('Thông tin người mua','custom'); ?></h3>
					<p><b><?php echo __('Họ tên người mua','custom'); ?>: </b><input type="text" name="tenkh" value="<?php echo @$custom["tenkh"][0]; ?>"></p>
					<p><b><?php echo __('Số điện thoại','custom'); ?>: </b><input type="text" name="dienthoai" value="<?php echo @$custom["dienthoai"][0]; ?>"></p>
					<p><b><?php echo __('Email','custom'); ?>: </b><input type="text" name="email" value="<?php echo @$custom["email"][0]; ?>"></p>
					<p><b><?php echo __('Địa chỉ','custom'); ?>: </b><input type="text" name="diachi" value="<?php echo @$custom["diachi"][0]; ?>"></p>
				</div>
			</div>
		</div>
	<?php
}
// add function save customer information
function save_purchase_info_custom_fields(){
	global $post;

	if ( $post )
	{
		update_post_meta($post->ID, "status", @$_POST["status"]);
		update_post_meta($post->ID, "nganhang", @$_POST["nganhang"]);
		update_post_meta($post->ID, "tensp", @$_POST["tensp"]);
		update_post_meta($post->ID, "linksp", @$_POST["linksp"]);
		update_post_meta($post->ID, "giaban", @$_POST["giaban"]);
		update_post_meta($post->ID, "thanhtoan", @$_POST["thanhtoan"]);
		update_post_meta($post->ID, "thoigian", @$_POST["thoigian"]);
		update_post_meta($post->ID, "laisuat", @$_POST["laisuat"]);
		update_post_meta($post->ID, "tientt", @$_POST["tientt"]);
		update_post_meta($post->ID, "tienht", @$_POST["tienht"]);
		update_post_meta($post->ID, "tenkh", @$_POST["tenkh"]);
		update_post_meta($post->ID, "dienthoai", @$_POST["dienthoai"]);
		update_post_meta($post->ID, "email", @$_POST["email"]);
		update_post_meta($post->ID, "diachi", @$_POST["diachi"]);

		update_post_meta($post->ID, "month", @$_POST["month"]);
		update_post_meta($post->ID, "date_publish", @$_POST["date_publish"]);
		update_post_meta($post->ID, "date_purchase", @$_POST["date_purchase"]);
	}
}
add_action( 'admin_init', 'add_purchase_info_meta_boxes' );
add_action( 'save_post', 'save_purchase_info_custom_fields' );

// add metabox status post type tragop
function add_status_meta_boxes() {
	add_meta_box("status_metabox", __('Tình trạng thanh toán','custom'), "add_status_meta_box", "tragop", "side", "high");
}
function add_status_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
	?>
		<select name="status">
			<option <?php if (@$custom["status"][0] == 0 ) echo 'selected' ; ?> value="0">Đang thanh toán</option>
			<option <?php if (@$custom["status"][0] == 1 ) echo 'selected' ; ?> value="1">Đã hoàn thành</option>
		</select>
	<?php
}
// add function save metabox status post type tragop
function save_metabox_status_tragop(){
	global $post;

	if ( $post )
	{
		update_post_meta($post->ID, "status", @$_POST["status"]);
	}
}
add_action( 'admin_init', 'add_status_meta_boxes' );
add_action( 'save_post', 'save_metabox_status_tragop' );

// add metabox cho phep mua tra gop
function add_enable_purchase() {
	add_meta_box("enable_purchase", __('Cho phép mua trả góp','custom'), "add_enable_purchase_meta_box", "product", "side", "high");
}
function add_enable_purchase_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
	?>
		<select name="enable_purchase">
			<option <?php if (@$custom["enable_purchase"][0] == 0 ) echo 'selected' ; ?> value="0">Không cho phép</option>
			<option <?php if (@$custom["enable_purchase"][0] == 1 ) echo 'selected' ; ?> value="1">Cho phép</option>
		</select>
	<?php
}
// add function save metabox status post type tragop
function save_metabox_enable_purchase(){
	global $post;

	if ( $post )
	{
		update_post_meta($post->ID, "enable_purchase", @$_POST["enable_purchase"]);
	}
}
add_action( 'admin_init', 'add_enable_purchase' );
add_action( 'save_post', 'save_metabox_enable_purchase' );


// add columns purchase_info and customer_info
add_filter('manage_tragop_posts_columns', 'ST4_columns_tragop_head');
add_action('manage_tragop_posts_custom_column', 'ST4_columns_tragop_content', 10, 2);

function ST4_columns_tragop_head($columns) {
	unset( $columns['date'] );
    $columns['first_column']  = __('Thông tin trả góp','custom');
    $columns['second_column'] = __('Thông tin khách hàng','custom');
    return $columns;
}
 
function ST4_columns_tragop_content($column_name, $post_ID) {
	global $post;
    if ($column_name == 'first_column') {    	
    	?>
    		<div class="info_purchase">
    			<p><b><?php echo __('Sản phẩm','custom'); ?>: </b><a href="<?php echo get_post_meta( $post->ID, 'linksp', true ); ?>" target="_blank"><?php echo get_post_meta( $post->ID, 'tensp', true ); ?></a></p>
				<p><b><?php echo __('Tổng giá trị sản phẩm','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'giaban', true ); ?></p>
				<p><b><?php echo __('Thanh toán trước','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'thanhtoan', true ); ?></p>
				<p><b><?php echo __('Thời gian thanh toán','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'thoigian', true ); ?></p>
				<p><b><?php echo __('Lãi suất','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'laisuat', true ); ?></p>
				<p><b><?php echo __('Số tiền thanh toán trước','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'tientt', true ); ?></p>
				<p><b><?php echo __('Số tiền đóng hàng tháng','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'tienht', true ); ?></p>
				<p><b><?php echo __('Ngày bắt đầu thanh toán','custom'); ?>: </b><?php echo get_the_date('d/m/Y'); ?></p>
    		</div>
    	<?php     
    }
    if ($column_name == 'second_column') {
        echo get_post_meta( $post->ID, 'info_customer', true );
        ?>
    		<div class="info_purchase">
    			<p><b><?php echo __('Họ tên người mua','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'tenkh', true ); ?></p>
				<p><b><?php echo __('Số điện thoại','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'dienthoai', true ); ?></p>
				<p><b><?php echo __('Email','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'email', true ); ?></p>
				<p><b><?php echo __('Địa chỉ','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'diachi', true ); ?></p>
    		</div>
    	<?php
    }
}

//add option page page_installment_purchase
if( function_exists('acf_add_options_page') ) {
  
  acf_add_options_page(array(
    'page_title'  => __('Thiết lập mua trả góp','custom'),
    'menu_title'  => __('Thiết lập mua trả góp','custom'),
    'menu_slug'   => 'page_installment_purchase',
    'capability'  => 'edit_posts',
    'icon_url'    => 'dashicons-pressthis',
    'redirect'    => false
  ));
}

// add field to page_installment_purchase
if( function_exists('acf_add_local_field_group') ):
	acf_add_local_field_group(array(
		'key' => 'setting_purchase',
		'title' => __('Thiết lập mua trả góp','custom'),
		'fields' => array (
			array (
				'key' => 'text_button',
				'label' => __('Tiêu đề nút mua trả góp','custom'),
				'name' => 'text_button',
				'type' => 'text',
				'placeholder' => __('Tiêu đề nút mua trả góp','custom'),
				'wrapper' => array (
					'width' => '30',
				),
			),
			array (
				'key' => 'link_page',
				'label' => __('Chọn trang đăng ký mua trả góp','custom'),
				'name' => 'link_page',
				'type' => 'page_link',
				'post_type' => 'page',
				'wrapper' => array (
					'width' => '40',
				),
			),
			array (
				'key' => 'link_success',
				'label' => __('Chọn trang thông báo đăng ký thành công','custom'),
				'name' => 'link_success',
				'type' => 'page_link',
				'post_type' => 'page',
				'wrapper' => array (
					'width' => '30',
				),
			),
			array(
				'key' => 'list_bank',
				'label' => __('Ngân hàng hỗ trợ trả góp','custom'),
				'name' => 'list_bank',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => __('Thêm ngân hàng','custom'),
				'sub_fields' => array(
					array (
						'key' => 'logo_bank',
						'label' => __('Logo ngân hàng','custom'),
						'name' => 'logo_bank',
						'type' => 'image',
						'return_format' => 'url',
						'preview_size' => 'full',
						'wrapper' => array (
							'width' => '33',
						),
					),
					array (
						'key' => 'title_bank',
						'label' => __('Tên ngân hàng','custom'),
						'name' => 'title_bank',
						'type' => 'text',
						'wrapper' => array (
							'width' => '33',
						),
					),
					array (
						'key' => 'lai_suat',
						'label' => __('Lãi xuất vay','custom'),
						'name' => 'lai_suat',
						'type' => 'group',
						'sub_fields' => array(
							array (
								'key' => 'min_3',
								'label' => __('Vay dưới 3 tháng (%)','custom'),
								'name' => 'min_3',
								'type' => 'number',
								'min' => '0',
							),
							array (
								'key' => 'between_3_6',
								'label' => __('Vay từ 3 - 6 tháng (%)','custom'),
								'name' => 'between_3_6',
								'type' => 'number',
								'min' => '0',
							),
							array (
								'key' => 'from_6',
								'label' => __('Vay trên 6 tháng (%)','custom'),
								'name' => 'from_6',
								'type' => 'number',
								'min' => '0',
							),
						),
						'wrapper' => array (
							'width' => '33',
						),
					),
					array (
						'key' => 'tra_truoc',
						'label' => __('% số tiền thanh toán trước','custom'),
						'name' => 'tra_truoc',
						'type' => 'checkbox',
						'choices' => array(
							'10'	=> '10%',
							'15'	=> '15%',
							'20'	=> '20%',
							'25'	=> '25%',
							'30'	=> '30%',
							'35'	=> '35%',
							'40'	=> '40%',
							'45'	=> '45%',
							'50'	=> '50%',
							'55'	=> '55%',
							'60'	=> '60%',
							'65'	=> '65%',
							'70'	=> '70%',
							'75'	=> '75%',
							'80'	=> '80%',
						),
						'return_format' => 'array',
						'wrapper' => array (
							'width' => '33',
						),
					),
					array (
						'key' => 'thoi_han',
						'label' => __('Thời hạn trả góp','custom'),
						'name' => 'thoi_han',
						'type' => 'checkbox',
						'layout' => 'horizontal',
						'choices' => array(
							'1'	=> '1 tháng',
							'2'	=> '2 tháng',
							'3'	=> '3 tháng',
							'4'	=> '4 tháng',
							'5'	=> '5 tháng',
							'6'	=> '6 tháng',
							'7'	=> '7 tháng',
							'8'	=> '8 tháng',
							'9'	=> '9 tháng',
							'10'	=> '10 tháng',
							'11'	=> '11 tháng',
							'12'	=> '12 tháng',
							'13'	=> '13 tháng',
							'14'	=> '14 tháng',
							'15'	=> '15 tháng',
							'16'	=> '16 tháng',
							'17'	=> '17 tháng',
							'18'	=> '18 tháng',							
						),
						'return_format' => 'array',
						'wrapper' => array (
							'width' => '33',
						),
					),
					array (
						'key' => 'min_price',
						'label' => __('Số tiền tối thiểu','custom'),
						'name' => 'min_price',
						'type' => 'number',
						'min' => '0',
						'wrapper' => array (
							'width' => '33',
						),
					),
				),
			),			
			array (
				'key' => 'message_success',
				'label' => __('Thông báo khi đăng ký thành công','custom'),
				'name' => 'message_success',
				'type' => 'text',
				'placeholder' => __('Đăng ký trả góp thành công. Chúng tôi sẽ liên hệ với bạn sau. Cảm ơn bạn !!!','custom'),
				'wrapper' => array (
					'width' => '50',
				),
			),
			array (
				'key' => 'message_error',
				'label' => __('Thông báo khi đăng ký bị lỗi','custom'),
				'name' => 'message_error',
				'type' => 'text',
				'placeholder' => __('Có lỗi xảy khi trong quá trình đăng ký. Vui lòng liên hệ với người quản trị của website.','custom'),
				'wrapper' => array (
					'width' => '50',
				),
			),
			array (
				'key' => 'active_message',
				'label' => __('Kích hoạt chức năng gửi mail','custom'),
				'name' => 'active_message',
				'type' => 'radio',
				'layout' => 'horizontal',
				'choices' => array(
					'1'	=> 'Kích hoạt',
					'0'	=> 'Hủy',							
				),
				'wrapper' => array (
					'width' => '70',
				),
			),
			array (
				'key' => 'time_message',
				'label' => __('Khoảng thời gian lặp lại việc gửi email (s)','custom'),
				'name' => 'time_message',
				'type' => 'number',
				'min' => '60',
				'placeholder' => '60',
				'wrapper' => array (
					'width' => '30',
				),
			),
			array (
				'key' => 'title_first',
				'label' => __('Tiêu đề email của lần gửi thông báo đầu tiên','custom'),
				'name' => 'title_first',
				'type' => 'text',
				'placeholder' => __('Thông báo thanh toán vay trả góp cho sản phẩm ','custom'),
				'wrapper' => array (
					'width' => '40',
				),
			),
			array (
				'key' => 'content_first',
				'label' => __('Nội dung email của lần gửi thông báo đầu tiên','custom'),
				'name' => 'content_first',
				'type' => 'text',
				'placeholder' => __('Chào bạn. Đây là email thông báo nhắc nhở bạn đến hạn vay trả góp.','custom'),
				'wrapper' => array (
					'width' => '60',
				),
			),
			array (
				'key' => 'title_another',
				'label' => __('Tiêu đề email của lần gửi thông báo sau','custom'),
				'name' => 'title_another',
				'type' => 'text',
				'placeholder' => __('Thông báo nhắc nhở thanh toán vay trả góp cho sản phẩm ','custom'),
				'wrapper' => array (
					'width' => '40',
				),
			),
			array (
				'key' => 'content_another',
				'label' => __('Nội dung email của lần gửi thông báo sau','custom'),
				'name' => 'content_another',
				'type' => 'text',
				'placeholder' => __('Chào bạn. Bạn đã đến hạn thanh toán vay trả góp. Vui lòng thực hiện thanh toán đúng hạn.','custom'),
				'wrapper' => array (
					'width' => '60',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'page_installment_purchase',
				),
			),
		),
	));
endif;

// setup a function to check if these pages exist
function the_slug_exists($post_name) {
	global $wpdb;
	if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
		return true;
	} else {
		return false;
	}
}
// add page "Mua tra gop" in frontend
if ( is_admin() ){
    $sitemap_page_title = 'Mua trả góp';
    $sitemap_page_check = get_page_by_title($sitemap_page_title);
    $sitemap_page = array(
	    'post_type' => 'page',
	    'post_content' => '[installment_purchase]',
	    'post_title' => $sitemap_page_title,
	    'post_status' => 'publish',
	    'post_author' => 1,
	    'post_slug' => 'mua-tra-gop',	    
    );
    if(!isset($sitemap_page_check->ID) && !the_slug_exists('site-map')){
        $sitemap_page_id = wp_insert_post($sitemap_page);
    }
}
// add page "Dang ky mua tra gop thanh cong"
if ( is_admin() ){
    $sitemap_page_title_2 = 'Đăng ký mua trả góp thành công';
    $sitemap_page_check_2 = get_page_by_title($sitemap_page_title_2);
    $sitemap_page_2 = array(
	    'post_type' => 'page',
	    'post_content' => '[success_purchase]',
	    'post_title' => $sitemap_page_title_2,
	    'post_status' => 'publish',
	    'post_author' => 1,
	    'post_slug' => 'dang-ky-mua-tra-gop-thanh-cong',	    
    );
    if(!isset($sitemap_page_check_2->ID) && !the_slug_exists('site-map-2')){
        $sitemap_page_id_2 = wp_insert_post($sitemap_page_2);
    }
}

// add button "Mua trả góp" on frontend
add_action( 'woocommerce_after_add_to_cart_button', 'add_btn_installment_purchase', 10, 0 );
function add_btn_installment_purchase() { 
	global $post;
    $my_custom_link = get_field('link_page','option');
    if( $my_custom_link ):
    	$my_custom_link = $my_custom_link;
    else:
    	$my_custom_link = site_url().'/mua-tra-gop';
    endif;
    if( get_field('text_button','option') ):
    	$text = get_field('text_button','option');
    else:
    	$text = __('Trả góp','custom');
    endif;
    ?>
    	<?php $enable_purchase = get_post_meta( $post->ID, 'enable_purchase', true ); ?>
    	<?php if( $enable_purchase == '1' ): ?>
	    	<div class="btn_purchase">
	    		<a href="<?php echo esc_url( $my_custom_link ); ?>?id=<?php echo $post->ID; ?>"><?php echo $text; ?></a>
	    	</div>
	    <?php endif; ?>
    <?php
}; 

// add shortcode view on frontend
function view_installment_purchase( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title'	=> '',
	), $atts ) );
	ob_start();
		$product_id = $_GET['id'];
		if( $product_id ):
			global  $woocommerce;

			$product = wc_get_product( $product_id );

			// get src image product 
			$img_id = $product->get_image_id();
			$img_src = wp_get_attachment_image_src( $img_id, full );
			
			// get product name
			$pro_name = $product->get_name();

			// get price product
			$product_price = $product->get_price();
			$regular_price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();

			?>
				<div class="row row-small layout_purchase">
					<div class="col medium-3 large-3 col_image">
						<div class="col-inner">					
							<div class="img_product">
								<a href="<?php echo get_permalink( $product->get_id() ); ?>" title="<?php echo $pro_name; ?>"><img src="<?php echo $img_src[0]; ?>" alt="<?php echo $pro_name; ?>"></a>								
							</div>
							<h3 class="product_title"><a href="<?php echo get_permalink( $product->get_id() ); ?>" title="<?php echo $pro_name; ?>"><?php echo $pro_name; ?></a></h3>
							<div class="product_price">
								<?php if( $sale_price ): ?>
									<div class="price">
										<p class="sale_price">
											<ins>
												<span class="amount"><?php echo number_format( $sale_price ); ?> <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span></span>
											</ins>
										</p>
										<p class="regular_price">
											<del>
												<span class="amount"><?php echo number_format( $regular_price ); ?> <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span></span>					
											</del>
										</p>										
									</div>
								<?php else: ?>
									<div class="price">
										<span class="amount"><?php echo number_format( $regular_price ); ?> <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span></span>							
									</div>
								<?php endif; ?>
							</div>						
						</div>
					</div>
					<div class="col medium-9 large-9 col_info">
						<div class="col-inner">					
							<h3 class="title_purchase"><?php echo __('Đăng ký mua trả góp','custom'); ?></h3>							
							<div class="list_bank">
								<?php if( have_rows('list_bank','option') ): ?>
									<ul class="tab-links nav">
									    <?php 
									    	$i = 1;
									    	while ( have_rows('list_bank','option') ) : the_row(); ?>
										    	<li class="tab <?php if( $i==1 ) echo 'active'; ?>">
						        					<a href="#tab<?php echo $i; ?>"><img src="<?php echo get_sub_field('logo_bank','option'); ?>"></a>
						        				</li>
								        <?php
								        	$i++; 
								    		endwhile;
							    		?>
							        </ul>
								<?php endif; ?>
							</div>
							<div class="infor_purchase">
								<?php if( have_rows('list_bank','option') ): ?>
									<?php 
								    	$i = 1;
								    	while ( have_rows('list_bank','option') ) : the_row(); ?>
									    	<div id="tab<?php echo $i; ?>" class="tab-ct <?php if( $i==1 ) echo 'active'; ?>">
									    		<form name="frm_purchase" class="frm_purchase_<?php echo $i; ?>"  action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
									    			<div class="row row-small layout_frmpurchase">
									    				<div class="col large-6 col_left">
									    					<div class="col-inner">
									    						<div class="frm_header">
																	<h1><?php echo __('Bảng dự toán mua hàng trả góp (Ước tính)','custom'); ?></h1>
																</div>
																<div class="frm_content">
																	<div class="row row-small frm_dutoan">
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Tổng giá trị sản phẩm','custom'); ?></p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<input type="text" readonly name="price_product" class="price_product" value="<?php echo $product_price; ?>">
																			</div>
																		</div>
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Thanh toán trước','custom'); ?></p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<select class="thanhtoan required" name="thanhtoan" required>
																					<option value="0" selected disabled><?php echo __('Thanh toán trước','custom'); ?></option>
																					<?php 
																						$tra_truoc = get_sub_field('tra_truoc','option');
																						if( $tra_truoc ):
																							foreach( $tra_truoc as $tratruoc ):
																								?>
																									<option value="<?php echo $tratruoc['value']; ?>"><?php echo $tratruoc['label']; ?></option>
																								<?php
																							endforeach;
																						endif;
																					?>
																				</select>
																				<div class="note_thanhtoan"></div>
																			</div>
																		</div>
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Thời hạn trả góp','custom'); ?></p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<select class="thoihan required" name="thoihan" required>
																					<option value="0" selected disabled><?php echo __('Thời hạn trả góp','custom'); ?></option>
																					<?php 
																						$thoi_han = get_sub_field('thoi_han','option');
																						if( $thoi_han ):
																							foreach( $thoi_han as $thoihan ):
																								?>
																									<option value="<?php echo $thoihan['value']; ?>"><?php echo $thoihan['label']; ?></option>
																								<?php
																							endforeach;
																						endif;
																					?>
																				</select>
																				<?php 
																					$lai_suat = get_sub_field('lai_suat','option');																					
																				?>
																				<input type="hidden" class="min_3" name="min_3" value="<?php echo $lai_suat['min_3']; ?>">
																				<input type="hidden" class="between_3_6" name="between_3_6" value="<?php echo $lai_suat['between_3_6']; ?>">
																				<input type="hidden" class="from_6" name="from_6" value="<?php echo $lai_suat['from_6']; ?>">
																			</div>
																		</div>
																		<div class="col ft_form pb-0">
																			<div class="col-inner">
																				<div class="thanhtoantruoc">
																					<p class="text"><?php echo __('Số tiền thanh toán trước','custom'); ?></p>
																					<p class="price"></p>
																				</div>
																				<div class="thanhtoan_hangthang">
																					<p class="text"><?php echo __('Số tiền thanh toán hàng tháng','custom'); ?></p>
																					<p class="price"></p>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
									    					</div>
									    				</div>
									    				<div class="col large-6 col_right">
									    					<div class="col-inner">
									    						<div class="frm_header">
																	<h1><?php echo __('Đăng ký trả góp','custom'); ?></h1>
																</div>
																<div class="frm_content">
																	<div class="row row-small frm_dangkytg">
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Tên của bạn','custom'); ?>:</p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<input type="text" name="your_name" class="required" autocomplete required>
																			</div>
																		</div>
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Số điện thoại','custom'); ?>:</p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<input type="tel" name="your_phone" class="required" autocomplete required>
																			</div>
																		</div>
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Email','custom'); ?>:</p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<input type="email" name="your_email" class="required" autocomplete required>
																			</div>
																		</div>
																		<div class="col medium-4">
																			<div class="col-inner">
																				<p><?php echo __('Địa chỉ','custom'); ?>:</p>
																			</div>
																		</div>
																		<div class="col medium-8">
																			<div class="col-inner">
																				<input type="text" name="your_address" class="required" autocomplete required>
																			</div>
																		</div>
																		<div class="col pb-0 ft_dangky">
																			<div class="col-inner">
																				<input class="dangky_tragop" type="submit" value="<?php echo __('Đăng ký ngay','custom'); ?>" >
																				<input type="hidden" name="option" value="tragop_<?php echo $i; ?>" />
																				<input type="hidden" class="tratruoc" name="tratruoc">
																				<input type="hidden" class="hangthang" name="hangthang">
																				<input type="hidden" class="laisuat" name="laisuat">
																				<input type="hidden" class="ten_sp" name="ten_sp" value="<?php echo $pro_name; ?>">
																				<input type="hidden" class="gia_sp" name="gia_sp">
																				<input type="hidden" class="link_sp" name="link_sp" value="<?php echo get_permalink( $product->get_id() ); ?>">	
																				<input type="hidden" class="title_bank" name="title_bank" value="<?php echo get_sub_field('title_bank','option'); ?>">
																				<input type="hidden" class="min_price" name="min_price" value="<?php echo get_sub_field('min_price','option'); ?>">	
																				<input type="hidden" class="check_min" name="check_min">		
																			</div>
																		</div>
																	</div>
																</div>
									    					</div>
									    				</div>
									    			</div>									    			
									    		</form>									    		
									    	</div>
									    	<!-- add script for event calc price purchase -->
												<script type="text/javascript">
													jQuery(function($) {														

														var formatter = new Intl.NumberFormat();

														var gia_sp = $('.price_product').attr("value");

														var frm_gia = formatter.format(gia_sp);

														// return value price product
														$('.frm_purchase_<?php echo $i; ?> input.gia_sp').attr( 'value', frm_gia );												 	

														// click  Thanh toan truoc
														$('.frm_purchase_<?php echo $i; ?> .thanhtoan').on('change', function() {															
															tratruoc();
															thoihan();																
														});

														// click Thoi han tra gop
														$('.frm_purchase_<?php echo $i; ?> .thoihan').on('change', function() {															
															thoihan();
															tratruoc();						
														});

														function tratruoc() {
															var thanhtoan  = $('.frm_purchase_<?php echo $i; ?> .thanhtoan option:selected').attr("value");
															var tratruoc;
															if( thanhtoan != 0 ){
																tratruoc = formatter.format(gia_sp * thanhtoan / 100);
																$('.frm_purchase_<?php echo $i; ?> .thanhtoantruoc .price').html('<span class="amount"></span> <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
																$('.frm_purchase_<?php echo $i; ?> .thanhtoantruoc .price .amount').text(tratruoc);
															}
															else
															{
																tratruoc = '';
																$('.frm_purchase_<?php echo $i; ?> .thanhtoantruoc .price').html(tratruoc);
															}
															var n_tratruoc = gia_sp * thanhtoan / 100;
															var p_tratruoc = gia_sp - n_tratruoc;
															var m_price = $('.frm_purchase_<?php echo $i; ?> input.min_price').val();
															var fm_price = formatter.format(m_price);
															if( p_tratruoc < m_price ){
																$('.note_thanhtoan').html('<?php echo __('Số tiền tối thiểu vay trả góp là ','custom'); ?>' + fm_price + ' <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
																$('.note_thanhtoan').show();

																$('.frm_purchase_<?php echo $i; ?> input.check_min').attr( 'value', 'false' );

																$('.frm_purchase_<?php echo $i; ?> input[type="submit"]').attr('disabled','disabled');
															}
															else
															{
																$('.note_thanhtoan').hide();

																$('.frm_purchase_<?php echo $i; ?> input.check_min').attr( 'value', 'true' );

																$('.frm_purchase_<?php echo $i; ?> input[type="submit"]').removeAttr('disabled');
															}

															// return value price
															var tratruoc_p = $('.frm_purchase_<?php echo $i; ?> .thanhtoantruoc .price .amount').html();
															$('.frm_purchase_<?php echo $i; ?> input.tratruoc').attr( 'value', tratruoc_p );

														}

														function thoihan() {
															var thoihan  = $('.frm_purchase_<?php echo $i; ?> .thoihan option:selected').attr("value");							
															var p_tratruoc = price_to_number($('.frm_purchase_<?php echo $i; ?> .thanhtoantruoc .price .amount').html());
															// alert( p_tratruoc );
															function price_to_number(v){
												                if(!v){return 0;}
												                // v = v.split('.').join('');
												                v = v.split(',').join('');
												                return v;
												            }
															var p_thang, laisuat;
															if( thoihan != 0 ){

																if( thoihan < 3 ){
																	laisuat = $('.frm_purchase_<?php echo $i; ?> .min_3').attr("value");	
																}
																if( thoihan >= 3 && thoihan <= 6 ){
																	laisuat = $('.frm_purchase_<?php echo $i; ?> .between_3_6').attr("value");
																}
																if( thoihan > 6 ){
																	laisuat = $('.frm_purchase_<?php echo $i; ?> .from_6').attr("value");	
																}

																p_thang = formatter.format( Math.ceil( ( gia_sp - p_tratruoc ) / thoihan + ( gia_sp - p_tratruoc ) * laisuat / 100 ) ) ;
																$('.frm_purchase_<?php echo $i; ?> .thanhtoan_hangthang .price').html('<span class="amount"></span> <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
																$('.frm_purchase_<?php echo $i; ?> .thanhtoan_hangthang .price .amount').text(p_thang);
															}
															else
															{
																p_thang = '';
																$('.frm_purchase_<?php echo $i; ?> .thanhtoan_hangthang .price').html(p_thang);
															}

															// return value price
															var thanhtoan_p = $('.frm_purchase_<?php echo $i; ?> .thanhtoan_hangthang .price .amount').html();
															$('.frm_purchase_<?php echo $i; ?> input.hangthang').attr( 'value', thanhtoan_p );

															// return value laisuat
															$('.frm_purchase_<?php echo $i; ?> input.laisuat').attr( 'value', laisuat );
														}														

													});
												</script>
											<!-- end script for event calc price purchase -->

											<!-- add event for active button submit form -->
												<script type="text/javascript">

													jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
													    phone_number = phone_number.replace(/\s+/g, "");
													    return this.optional(element) || phone_number.length > 9 && 
													    phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
													}, "Please specify a valid phone number");

													jQuery(function($) {
													 	$(".frm_purchase_<?php echo $i; ?>").validate({
															rules: {
																your_email: {
																	required: true,
																	email: true
																},
																your_phone: {
																	phoneUS: true
																},																
															},
															messages: {
																your_name: "<?php echo __('Bạn phải nhập tên.','custom'); ?>",
																your_phone: "<?php echo __('Bạn phải nhập số điện thoại.','custom'); ?>",
																your_email: "<?php echo __('Vui lòng nhập một địa chỉ email hợp lệ.','custom'); ?>",
																your_address: "<?php echo __('Bạn phải nhập địa chỉ.','custom'); ?>",
																thanhtoan: "<?php echo __('Chọn số tiền thanh toán trước.','custom'); ?>",
																thoihan: "<?php echo __('Chọn thời hạn trả góp.','custom'); ?>",
															}
														});
													});
												</script>
											<!-- end event for active button submit form -->

							        <?php
							        	$i++; 
							    		endwhile;
						    		?>
								<?php endif; ?>								
							</div>
							<!-- show message -->
							<?php write_here_show_error_messages(); ?>
						</div>
					</div>
				</div>

				<!-- add javascript for tab list bank -->
					<script type="text/javascript" defer>
						jQuery(function($) {
							// add event for tab list bank
							$('.list_bank .tab-links a').on('click', function(e) {
						        var currentAttrValue = $(this).attr('href');

						        // Show/Hide Tabs
						        $(currentAttrValue).addClass('active');
						        $(currentAttrValue).siblings().removeClass('active');
						        $(currentAttrValue).show('slideDown');
						        $(currentAttrValue).siblings().hide('slideUp');

						        // Change/remove current tab to active
						        $(this).parent('li').addClass('active');
						        $(this).parent('li').siblings().removeClass('active');

						        e.preventDefault();
						    });

						    $('.list_bank ul.tab-links li:first-child a').trigger('click');

						});
					</script>
				<!-- end javascript for tab list bank -->			

			<?php
		endif;		
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('installment_purchase', 'view_installment_purchase');

// function excute submit form register
function excute_submit_form() {
    if( have_rows('list_bank','option') ):
    	$i = 1;
		while ( have_rows('list_bank','option') ) : the_row();
			if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['option'] ) &&  $_POST['option'] == 'tragop_'.$i) :
				if( $_POST['check_min'] == 'true' ):
					$my_post = array(
					  	'post_title'    => __('Thông tin trả góp của khách hàng ').$_POST['your_name'].' - '.$_POST['your_phone'],
					  	'post_status'   => 'pending',		  	
						'post_type' => 'tragop',
						'meta_input'    => array(
							'nganhang' => $_POST['title_bank'],
						    'tensp' => $_POST['ten_sp'],
						    'linksp' => $_POST['link_sp'],
						    'giaban' => $_POST['gia_sp'].' '.get_woocommerce_currency_symbol(),
						    'thanhtoan'    => $_POST['thanhtoan'].'%',
						    'thoigian' => $_POST['thoihan'].' '.__('tháng','custom'),
						    'laisuat' => $_POST['laisuat'].'%',
						    'tientt' => $_POST['tratruoc'].' '.get_woocommerce_currency_symbol(),
						    'tienht' => $_POST['hangthang'].' '.get_woocommerce_currency_symbol(),
						    'tenkh' => $_POST['your_name'],
						    'dienthoai' => $_POST['your_phone'],
						    'email' => $_POST['your_email'],
						    'diachi' => $_POST['your_address'],

						    'month' => $_POST['thoihan'],
						),
					);

					$errors = write_here_errors()->get_error_messages();
					if(empty($errors)):
						$post_id = wp_insert_post( $my_post );

						if( $post_id ) :
			                $success_purchase = get_field('link_success','option');
						    if( $success_purchase ):
						    	$success_purchase = $success_purchase;
						    else:
						    	$success_purchase = site_url().'/dang-ky-mua-tra-gop-thanh-cong';
						    endif;		                
			                wp_redirect($success_purchase.'?id='.$post_id);
			                exit();
			            endif;
					endif;
				endif;
			endif;
			$i++; 
		endwhile;
    endif;
}
add_action('init', 'excute_submit_form');

// used for tracking error messages
function write_here_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function write_here_show_error_messages() {
    if($codes = write_here_errors()->get_error_codes()) :
        ?>
    		<div class="m_error">
    			<?php 
    				if( get_field('message_error','option') ):
						$mess_error = get_field('message_error','option'); 
					else:
						$mess_error = __('Có lỗi xảy khi trong quá trình đăng ký. Vui lòng liên hệ với người quản trị của website.','custom'); 
					endif;
					echo $mess_error;
    			?>
    		</div>
    	<?php
    endif;   
}

// add shortcode success_purchase
function success_purchase() {
	ob_start();
		$order = $_GET['id'];
		if( $order ):
			// show message
			if( get_field('message_success','option') ):
				$mess_success = get_field('message_success','option'); 
			else:
				$mess_success = __('Đăng ký trả góp thành công. Chúng tôi sẽ liên hệ với bạn sau. Cảm ơn bạn !!!','custom'); 
			endif;
			$message = $mess_success;

			$post = get_post( $order );
			?>
				<div class="m_success"><?php echo $message; ?></div>
				<div class="row customer_info">
					<div class="col large-7 medium-6 info_purchase">
						<div class="col-inner">
							<h3 class="title"><?php echo __('Thông tin trả góp','custom'); ?></h3>
							<p><b><?php echo __('Sản phẩm','custom'); ?>: </b><a href="<?php echo get_post_meta( $post->ID, 'linksp', true ); ?>" target="_blank"><?php echo get_post_meta( $post->ID, 'tensp', true ); ?></a></p>
							<p><b><?php echo __('Tổng giá trị sản phẩm','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'giaban', true ); ?></p>
							<div class="space"></div>
							<p><b><?php echo __('Thanh toán trước','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'thanhtoan', true ); ?></p>
							<p><b><?php echo __('Thời gian thanh toán','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'thoigian', true ); ?></p>
							<p><b><?php echo __('Lãi suất','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'laisuat', true ); ?></p>
							<p><b><?php echo __('Ngân hàng','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'nganhang', true ); ?></p>
							<div class="space"></div>
							<p><b><?php echo __('Số tiền thanh toán trước','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'tientt', true ); ?></p>
							<p><b><?php echo __('Số tiền đóng hàng tháng','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'tienht', true ); ?></p>
						</div>
					</div>
					<div class="col large-5 medium-6 info_customer">
						<div class="col-inner">
							<h3 class="title"><?php echo __('Thông tin người mua','custom'); ?></h3>
							<p><b><?php echo __('Họ tên người mua','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'tenkh', true ); ?></p>
							<p><b><?php echo __('Số điện thoại','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'dienthoai', true ); ?></p>
							<p><b><?php echo __('Email','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'email', true ); ?></p>
							<p><b><?php echo __('Địa chỉ','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'diachi', true ); ?></p>
						</div>
					</div>
				</div>
			<?php
		endif;
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'success_purchase', 'success_purchase' );

// add function send email notification when publish post type
add_action( 'transition_post_status', 'send_mails_on_publish', 10, 3 );
function send_mails_on_publish( $new_status, $old_status, $post )
{
	if( 'publish' !== $new_status or 'publish' === $old_status
    or 'tragop' !== get_post_type( $post ) )
	return;

	$email = get_post_meta( $post->ID, 'email', true );

	if( get_field('title_first','option') ):
		$subject = get_field('title_first','option');
	else:
		$subject = __('Thông báo thanh toán vay trả góp cho sản phẩm ','custom').get_post_meta( $post->ID, 'tensp', true );
	endif;

	if( get_field('content_first','option') ):
		$body .= get_field('content_first','option');
	else:
		$body .= __('Chào bạn. Đây là email thông báo nhắc nhở bạn đến hạn vay trả góp.','custom');
	endif;

	$body .= '<h3>'.__('Thông tin trả góp','custom').'</h3>';
	$body .= '<p><b>'.__('Sản phẩm','custom').': </b><a href="'.get_post_meta( $post->ID, 'linksp', true ).'" target="_blank">'.get_post_meta( $post->ID, 'tensp', true ).'</a></p>';
	$body .= '<p><b>'.__('Tổng giá trị sản phẩm','custom').': </b>'.get_post_meta( $post->ID, 'giaban', true ).'</p>';
	$body .= '<p><b>'.__('Thanh toán trước','custom').': </b>'.get_post_meta( $post->ID, 'thanhtoan', true ).'</p>';
	$body .= '<p><b>'.__('Thời gian thanh toán','custom').': </b>'.get_post_meta( $post->ID, 'thoigian', true ).'</p>';
	$body .= '<p><b>'.__('Lãi suất','custom').': </b>'.get_post_meta( $post->ID, 'laisuat', true ).'</p>';
	$body .= '<p><b>'.__('Ngân hàng','custom').': </b>'.get_post_meta( $post->ID, 'nganhang', true ).'</p>';
	$body .= '<p><b>'.__('Số tiền thanh toán trước','custom').': </b>'.get_post_meta( $post->ID, 'tientt', true ).'</p>';
	$body .= '<p><b>'.__('Số tiền đóng hàng tháng','custom').': </b>'.get_post_meta( $post->ID, 'tienht', true ).'</p>';

	$body .= '<div class="space" style="height: 7px;"></div>';

	$body .= '<h3 class="title">'.__('Thông tin người mua','custom').'</h3>';
	$body .= '<p><b>'.__('Họ tên người mua','custom').': </b>'.get_post_meta( $post->ID, 'tenkh', true ).'</p>';
	$body .= '<p><b>'.__('Số điện thoại','custom').': </b>'.get_post_meta( $post->ID, 'dienthoai', true ).'</p>';
	$body .= '<p><b>'.__('Email','custom').': </b>'.get_post_meta( $post->ID, 'email', true ).'</p>';
	$body .= '<p><b>'.__('Địa chỉ','custom').': </b>'.get_post_meta( $post->ID, 'diachi', true ).'</p>';


	$headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $email, $subject, $body, $headers );
}

// check active send email
if( get_field('active_message','option') == '1' ):	

	function isa_add_cron_recurrence_interval( $schedules ) {
		if( get_field('time_message','option') ):
			$time = get_field('time_message','option');
		else:
			$time = '43200';
		endif; 
	    $schedules['every_one_minutes'] = array(
            'interval'  => $time, // 12 hours
            'display'   => __( 'Every 1 Minutes', 'custom' )
	    );	     
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'isa_add_cron_recurrence_interval' );

	if ( ! wp_next_scheduled( 'your_three_minute_action_hook' ) ) {
	    wp_schedule_event( time(), 'every_one_minutes', 'your_three_minute_action_hook' );
	}

	add_action('your_three_minute_action_hook', 'isa_test_cron_job_send_mail'); 
	function isa_test_cron_job_send_mail() {
	    $args = array(
		    'posts_per_page' => -1,
		    'post_type' => 'tragop',
		    'meta_query' => array(
		        array(
		            'key'     => 'status',
		            'value'   => '0',
		            'compare' => '=',
		        ),
		    ),
		); 
		$ids = array();
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				$date_publish = get_the_date('m/d/Y');
				$date_current = date('m/d/Y');

				$date_diff = $date_current - $date_publish;

				$v_current = strtotime($date_current);
				$v_publish = strtotime($date_publish);

				// check month current with month publish
				$year1 = date('Y', $v_publish);
				$year2 = date('Y', $v_current);

				$month1 = date('m', $v_publish);
				$month2 = date('m', $v_current);

				$day1 = date('d', $v_publish);
				$day2 = date('d', $v_current);

				$diff = (($year2 - $year1) * 12) + ($month2 - $month1) - 1;

				// set date expire
				$month = get_post_meta( get_the_ID(), 'month', true );
				$date_3 = get_the_date('m/d/Y');
				$date_expire = strtotime($date_3 . "+".$month." month");
				$expire = date('m/d/Y', $date_expire);
				$v_expire = strtotime($expire);

				if( $v_current <= $v_expire ):
					$d_current = date('d', $v_current);
					$d_publish = date('d', $v_publish);
					$day_temp = strtotime($date_publish . "+20 days +".$diff." month");
					$d_temp = date('d', $day_temp);
					if( $d_current == $d_temp ):
						array_push($ids, get_the_ID());
					endif;								
				endif;
			endwhile;
		endif;
		wp_reset_query();
		$ids = implode(',', $ids);
		if ( !empty( $ids ) ):
			$ids = explode( ',', $ids );
			$ids = array_map( 'trim', $ids );
			$args2 = array(
				'post__in' => $ids,
		        'post_type' => 'tragop',
				'numberposts' => -1,
				'orderby' => 'post__in',
				'posts_per_page' => 9999,
				'ignore_sticky_posts' => true,
			);
			$query2 = new WP_Query( $args2 );
			if ( $query2->have_posts() ) :
				while ( $query2->have_posts() ) : $query2->the_post();
					$to = get_post_meta( get_the_ID(), 'email', true );
					if( get_field('title_another','option') ):
						$subject = get_field('title_another','option');
					else:
						$subject = __('Thông báo nhắc nhở thanh toán vay trả góp cho sản phẩm ','custom').get_post_meta( get_the_ID(), 'tensp', true ).'__'.get_post_meta( get_the_ID(), 'dienthoai', true );
					endif;
					if( get_field('content_another','option') ):
						$body = get_field('content_another','option');
					else:
						$body = __('Chào bạn. Bạn đã đến hạn thanh toán vay trả góp. Vui lòng thực hiện thanh toán đúng hạn.','custom');
					endif;

					$body .= '<h3>'.__('Thông tin trả góp','custom').'</h3>';
					$body .= '<p><b>'.__('Sản phẩm','custom').': </b><a href="'.get_post_meta( get_the_ID(), 'linksp', true ).'" target="_blank">'.get_post_meta( get_the_ID(), 'tensp', true ).'</a></p>';
					$body .= '<p><b>'.__('Tổng giá trị sản phẩm','custom').': </b>'.get_post_meta( get_the_ID(), 'giaban', true ).'</p>';
					$body .= '<p><b>'.__('Thanh toán trước','custom').': </b>'.get_post_meta( get_the_ID(), 'thanhtoan', true ).'</p>';
					$body .= '<p><b>'.__('Thời gian thanh toán','custom').': </b>'.get_post_meta( get_the_ID(), 'thoigian', true ).'</p>';
					$body .= '<p><b>'.__('Lãi suất','custom').': </b>'.get_post_meta( get_the_ID(), 'laisuat', true ).'</p>';
					$body .= '<p><b>'.__('Ngân hàng','custom').': </b>'.get_post_meta( $post->ID, 'nganhang', true ).'</p>';
					$body .= '<p><b>'.__('Số tiền thanh toán trước','custom').': </b>'.get_post_meta( get_the_ID(), 'tientt', true ).'</p>';
					$body .= '<p><b>'.__('Số tiền đóng hàng tháng','custom').': </b>'.get_post_meta( get_the_ID(), 'tienht', true ).'</p>';

					$body .= '<div class="space" style="height: 7px;"></div>';

					$body .= '<h3 class="title">'.__('Thông tin người mua','custom').'</h3>';
					$body .= '<p><b>'.__('Họ tên người mua','custom').': </b>'.get_post_meta( get_the_ID(), 'tenkh', true ).'</p>';
					$body .= '<p><b>'.__('Số điện thoại','custom').': </b>'.get_post_meta( get_the_ID(), 'dienthoai', true ).'</p>';
					$body .= '<p><b>'.__('Email','custom').': </b>'.get_post_meta( get_the_ID(), 'email', true ).'</p>';
					$body .= '<p><b>'.__('Địa chỉ','custom').': </b>'.get_post_meta( get_the_ID(), 'diachi', true ).'</p>';

					$headers = array('Content-Type: text/html; charset=UTF-8');
					 
					wp_mail( $to, $subject, $body, $headers );
				endwhile;
			endif;
			wp_reset_query(); 
		endif;
	}

else:	
	wp_clear_scheduled_hook( 'your_three_minute_action_hook' );
endif;


// remove button Add Banking
function remove_add_banking(){
	global $current_user;
    wp_get_current_user();
    //An bot menu item cho client de quan ly'
	$user_info = get_userdata(1);
	if( $current_user->user_login != 'vina_admin' )
	{
		?>
			<style type="text/css">
				.acf-field-list-bank .acf-actions
				{
					display: none;
				}
			</style>
		<?php
	}
}
add_action( 'admin_init', 'remove_add_banking' );