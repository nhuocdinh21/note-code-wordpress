<?php
/**
 * Plugin Name: Thu cũ đổi mới
 * Plugin URI: https://phongmy.vn/
 * Description: Tính phí thu cũ đổi mới sản phẩm
 * Version: 1.0
 * Author: phongmy.vn
 * Author URI: https://phongmy.vn/
 * License: GPLv2
 */

if( ! defined( 'ABSPATH' ) ) exit;

define('PLUGIN_URL', plugin_dir_url(__FILE__));
define('PLUGIN_PATH', plugin_dir_path(__FILE__));

// check has active plugin woocommerce
if ( !function_exists('is_plugin_active') ) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if ( !is_plugin_active('woocommerce/woocommerce.php') ) {
	deactivate_plugins('thu-cu-doi-moi/functions.php'); 
	return;
}

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
function plugin_style_admin() {
	wp_enqueue_style('style_admin', plugins_url('/css/admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'plugin_style_admin');

// add frontend css
function style_frontend() {   
	// add bootstrap.css
	wp_enqueue_style('modal', plugins_url('/css/modal.css', __FILE__));    
	// add style frontend
    wp_enqueue_style('frontend_css', plugins_url('/css/style.css', __FILE__));    
}
add_action('wp_enqueue_scripts', 'style_frontend');

// add frontend js
function plugin_enqueue_scripts() {
	// add validate
	wp_enqueue_script('validate', plugins_url( '/js/jquery.validate.min.js', __FILE__ ));	

	// add modal bootstrap	
	wp_enqueue_script('bootstrap-modalmanager', plugins_url( '/js/bootstrap-modalmanager.js', __FILE__ ));
	wp_enqueue_script('bootstrap-modal', plugins_url( '/js/bootstrap-modal.js', __FILE__ ));

	wp_enqueue_script ('pm_script_ajax_plugin', plugins_url( '/js/plugin_ajax.js', __FILE__ ));
	//the_ajax_script will use to print admin-ajaxurl in custom ajax.js
	wp_localize_script('pm_script_ajax_plugin', 'the_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action( 'wp_footer', 'plugin_enqueue_scripts' );

// add post type list Order Products
add_action( 'init', 'list_order_product' ); 
function list_order_product() {   

    $labels = array( 
        'name' => __('Thu cũ đổi mới', 'custom'), 
        'add_new' => __('Thêm mới', 'custom'), 
        'add_new_item' => __('Thêm mới','custom'), 
        'edit_item' => __('Sửa thông tin','custom'), 
        'new_item' => __('Thêm mới','custom'), 

        'view_item' => __('Xem thông','custom'), 
        'search_items' => __('Tìm kiếm','custom'), 
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
        'rewrite' => array( 'slug' => 'order_product', 'with_front'=> false ), 
        'capability_type' => 'post',
        'hierarchical' => true,
        'has_archive' => false,  
        'menu_position' => 5, 
        'supports' => array('title'),
    );
    register_post_type( 'order_product' , $args );
}

// order custom post type
function plugin_post_type_admin_order( $wp_query ) {
  if (is_admin()) {
    // Get the post type from the query
    $post_type = $wp_query->query['post_type'];
    if ( $post_type == 'order_product' ) {
      $wp_query->set('orderby', 'date');
      $wp_query->set('order', 'DESC');
    }
  }
}
add_filter('pre_get_posts', 'plugin_post_type_admin_order');

// add meta box save customer information
function add_order_info_meta_boxes() {
	add_meta_box("order_meta_box", __('Thông tin đơn hàng','custom'), "add_info_customer_meta_box", "order_product", "normal", "high");
}
function add_info_customer_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
	?>
		<div class="row customer_info">
			<div class="col large-6 small-12 info_purchase admin_style">
				<div class="col-inner">
					<h3 class="title"><?php echo __('Thông tin sản phẩm','custom'); ?></h3>
					<?php $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(@$custom["product_id"][0]) ); ?>
					<?php if( $featured_image ): ?>
						<p class="text-center"><img src="<?php echo $featured_image[0]; ?>"></p>
					<?php endif; ?>
					<p><b><?php echo __('Tên sản phẩm','custom'); ?>: </b><input type="text" readonly name="new_product_name" value="<?php echo @$custom["new_product_name"][0]; ?>"></p>
					<p><b><?php echo __('Giá máy mới','custom'); ?>: </b><input type="text" readonly name="new_product_price" value="<?php echo @$custom["new_product_price"][0]; ?>"></p>
					<p><b><?php echo __('Trợ giá','custom'); ?>: </b><input type="text" readonly name="subsidy_product_price" value="<?php echo @$custom["subsidy_product_price"][0]; ?>"></p>
					<p><b><?php echo __('Giá máy cũ thu lại','custom'); ?>: </b><input type="text" readonly name="odd_product_price" value="<?php echo @$custom["odd_product_price"][0]; ?>"></p>
					<p><b><?php echo __('Giá bù chênh lệch','custom'); ?>: </b><input type="text" readonly name="compensation_price" value="<?php echo @$custom["compensation_price"][0]; ?>"></p>
				</div>
			</div>
			<div class="col large-6 small-12 info_customer admin_style">
				<div class="col-inner">
					<h3 class="title"><?php echo __('Thông tin khách hàng','custom'); ?></h3>
					<p><b><?php echo __('Họ & tên','custom'); ?>: </b><input type="text" name="customer_name" value="<?php echo @$custom["customer_name"][0]; ?>"></p>
					<p><b><?php echo __('Số điện thoại','custom'); ?>: </b><input type="text" name="customer_phone" value="<?php echo @$custom["customer_phone"][0]; ?>"></p>
					<p><b><?php echo __('Email','custom'); ?>: </b><input type="text" name="customer_email" value="<?php echo @$custom["customer_email"][0]; ?>"></p>
					<p><b><?php echo __('Địa điểm thu máy','custom'); ?>: </b><input type="text" name="customer_address" value="<?php echo @$custom["customer_address"][0]; ?>"></p>
					<p><b><?php echo __('Ghi chú','custom'); ?>: </b><textarea name="customer_message" cols="30" rows="10"><?php echo @$custom["customer_message"][0]; ?></textarea></p>
					<p><b><?php echo __('Ghi chú từ người mua','custom'); ?>: </b><input type="text" name="customer_note" value="<?php echo @$custom["customer_note"][0]; ?>"></p>
				</div>
			</div>
		</div>
	<?php
}
// add function save customer information
function save_order_info_custom_fields(){
	global $post;

	if ( $post )
	{		
		update_post_meta($post->ID, "customer_name", @$_POST["customer_name"]);
		update_post_meta($post->ID, "customer_phone", @$_POST["customer_phone"]);
		update_post_meta($post->ID, "customer_email", @$_POST["customer_email"]);
		update_post_meta($post->ID, "customer_address", @$_POST["customer_address"]);
		update_post_meta($post->ID, "customer_message", @$_POST["customer_message"]);
		update_post_meta($post->ID, "customer_note", @$_POST["customer_note"]);

		update_post_meta($post->ID, "product_id", @$_POST["product_id"]);
		update_post_meta($post->ID, "odd_product_name", @$_POST["odd_product_name"]);
		update_post_meta($post->ID, "odd_product_price", @$_POST["odd_product_price"]);
		update_post_meta($post->ID, "product_type", @$_POST["product_type"]);
		update_post_meta($post->ID, "new_product_name", @$_POST["new_product_name"]);
		update_post_meta($post->ID, "new_product_price", @$_POST["new_product_price"]);
		update_post_meta($post->ID, "subsidy_product_price", @$_POST["subsidy_product_price"]);
		update_post_meta($post->ID, "compensation_price", @$_POST["compensation_price"]);
	}
}
add_action( 'admin_init', 'add_order_info_meta_boxes' );
add_action( 'save_post', 'save_order_info_custom_fields' );

// add columns purchase_info and customer_info
add_filter('manage_order_product_posts_columns', 'custom_columns_order_product_head');
add_action('manage_order_product_posts_custom_column', 'custom_columns_order_product_content', 10, 2);

function custom_columns_order_product_head($columns) {
	unset( $columns['date'] );
    $columns['first_column']  = __('Thông tin sản phẩm','custom');
    $columns['second_column'] = __('Thông tin khách hàng','custom');
    return $columns;
}
 
function custom_columns_order_product_content($column_name, $post_ID) {
	global $post;
    if ($column_name == 'first_column') {    	
    	?>
    		<div class="info_purchase">
    			<p><b><?php echo __('Sản phẩm','custom'); ?>: </b><a href="<?php echo get_permalink( get_post_meta( $post->ID, 'product_id', true ) ); ?>" target="_blank"><?php echo get_post_meta( $post->ID, 'new_product_name', true ); ?></a></p>

    			<?php if( get_post_meta( $post->ID, 'new_product_price', true ) != '' ): ?>
	    			<p><b><?php echo __('Giá máy mới','custom'); ?>: </b><?php echo number_format(get_post_meta( $post->ID, 'new_product_price', true ), 0, ',', '.'); ?> ₫</p>
					<p><b><?php echo __('Trợ giá','custom'); ?>: </b><?php echo number_format(get_post_meta( $post->ID, 'subsidy_product_price', true ), 0, ',', '.'); ?> ₫</p>
					<p><b><?php echo __('Giá máy cũ thu lại','custom'); ?>: </b><?php echo number_format(get_post_meta( $post->ID, 'odd_product_price', true ), 0, ',', '.'); ?> ₫</p>
					<p><b><?php echo __('Giá bù chênh lệch','custom'); ?>: </b><?php echo number_format(get_post_meta( $post->ID, 'compensation_price', true ), 0, ',', '.'); ?> ₫</p>
				<?php else: ?>
					<p><b><?php echo __('Giá máy cũ thu lại','custom'); ?>: </b><?php echo number_format(get_post_meta( $post->ID, 'odd_product_price', true ), 0, ',', '.'); ?> ₫</p>
				<?php endif; ?>
    		</div>
    	<?php     
    }
    if ($column_name == 'second_column') {
        echo get_post_meta( $post->ID, 'info_customer', true );
        ?>
    		<div class="info_purchase">
    			<p><b><?php echo __('Họ & tên','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'customer_name', true ); ?></p>
				<p><b><?php echo __('Số điện thoại','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'customer_phone', true ); ?></p>
				<p><b><?php echo __('Email','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'customer_email', true ); ?></p>
				<p><b><?php echo __('Địa điểm thu máy','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'customer_address', true ); ?></p>
				<p><b><?php echo __('Tin nhắn','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'customer_message', true ); ?></p>
				<p><b><?php echo __('Ghi chú từ người mua','custom'); ?>: </b><?php echo get_post_meta( $post->ID, 'customer_note', true ); ?></p>
    		</div>
    	<?php
    }
}

//add option page setting_buy_odd_renew_product
if( function_exists('acf_add_options_page') ) {
  
	acf_add_options_page(array(
		'page_title'  => __('Cài đặt Thu cũ đổi mới','custom'),
		'menu_title'  => __('Cài đặt Thu cũ đổi mới','custom'),
		'menu_slug'   => 'setting_buy_odd_renew_product',
		'capability'  => 'edit_posts',
		'icon_url'    => 'dashicons-pressthis',
		'redirect'    => false,
		'position'    => '6',
	));
}

// add field to setting_buy_odd_renew_product
if( function_exists('acf_add_local_field_group') ):
	acf_add_local_field_group(array(
		'key' => 'setting_information_buyodd_renew',
		'title' => __('Thiết lập Thông tin mua cũ đổi mới','custom'),
		'fields' => array (
			array (
				'key' => 'tab_layout_page_plugin',
				'label' => __('Bố cục trang Thu cũ đổi mới','custom'),
				'name' => 'tab_layout_page_plugin',
				'type' => 'tab',
			),
			array (
				'key' => 'content_header_page_plugin',
				'label' => __('Nội dung đầu trang','custom'),
				'name' => 'content_header_page_plugin',
				'type' => 'wysiwyg',
			),
			array (
				'key' => 'tab_layout_box_policy_plugin',
				'label' => __('Thông tin quy định','custom'),
				'name' => 'tab_layout_box_policy_plugin',
				'type' => 'tab',
			),
			array (
				'key' => 'content_information_policy',
				'label' => __('Thông tin quy định','custom'),
				'name' => 'content_information_policy',
				'type' => 'wysiwyg',
			),
			array (
				'key' => 'content_video_policy_plugin',
				'label' => __('Video hướng dẫn','custom'),
				'name' => 'content_video_policy_plugin',
				'type' => 'wysiwyg',
			),
			array (
				'key' => 'content_contact_policy_plugin',
				'label' => __('Thông tin liên hệ','custom'),
				'name' => 'content_contact_policy_plugin',
				'type' => 'wysiwyg',
			),
			array (
				'key' => 'tab_fontend_list_product',
				'label' => __('Danh sách sản phẩm','custom'),
				'name' => 'tab_fontend_list_product',
				'type' => 'tab',
			),
			array (
				'key' => 'title_frm_search_product',
				'label' => __('Tiêu đề form tìm kiếm','custom'),
				'name' => 'title_frm_search_product',
				'type' => 'text',
				'default_value' => 'Nhập tên sản phẩm bạn muốn định giá!',
				'placeholder' => __('Nhập tiêu đề form tìm kiếm','custom'),
			),
			array (
				'key' => 'list_load_product_cat',
				'label' => __('Chọn danh mục sản phẩm hiển thị','custom'),
				'name' => 'list_load_product_cat',
				'type' => 'taxonomy',
				'taxonomy' => 'product_cat',
				'field_type' => 'multi_select',
				'return_format' => 'object',
			),
			array (
				'key' => 'number_product_per_page',
				'label' => __('Số lượng sản phẩm trên 1 trang','custom'),
				'name' => 'number_product_per_page',
				'type' => 'number',
				'default_value' => '20',
				'min'     => '0',
				'append' => 'sản phẩm',
			),
			array (
				'key' => 'tab_product_status',
				'label' => __('Tình trạng máy cũ','custom'),
				'name' => 'tab_product_status',
				'type' => 'tab',
			),
			array (
				'key' => 'title_product_type_1',
				'label' => __('Tiêu đề ngoại hình máy loại 1','custom'),
				'name' => 'title_product_type_1',
				'type' => 'text',
				'default_value' => 'Máy hoạt động bình thường, màn đẹp, thân máy đẹp.',
				'placeholder' => __('Nhập tiêu đề ngoại hình máy loại 1','custom'),
			),
			array (
				'key' => 'title_product_type_2',
				'label' => __('Tiêu đề ngoại hình máy loại 2','custom'),
				'name' => 'title_product_type_2',
				'type' => 'text',
				'default_value' => 'Máy hoạt động bình thường, màn đẹp, thân máy trầy xước nhẹ.',
				'placeholder' => __('Nhập tiêu đề ngoại hình máy loại 2','custom'),
			),
			array (
				'key' => 'title_product_type_3',
				'label' => __('Tiêu đề ngoại hình máy loại 3','custom'),
				'name' => 'title_product_type_3',
				'type' => 'text',
				'default_value' => 'Máy hoạt động bình thường, màn trầy nhẹ, thân máy cấn móp nhẹ.',
				'placeholder' => __('Nhập tiêu đề ngoại hình máy loại 3','custom'),
			),
			array (
				'key' => 'title_product_type_4',
				'label' => __('Tiêu đề ngoại hình máy loại 4','custom'),
				'name' => 'title_product_type_4',
				'type' => 'text',
				'default_value' => 'Máy hoạt động bình thường, ngoại hình xấu nhưng màn còn hiển thị và cảm ứng được.',
				'placeholder' => __('Nhập tiêu đề ngoại hình máy loại 4','custom'),
			),
			array (
				'key' => 'tab_san_pham_len_doi',
				'label' => __('Sản phẩm lên đời','custom'),
				'name' => 'tab_san_pham_len_doi',
				'type' => 'tab',
			),
			array (
				'key' => 'list_load_new_product_cat',
				'label' => __('Chọn danh mục máy mới','custom'),
				'name' => 'list_load_new_product_cat',
				'type' => 'taxonomy',
				'taxonomy' => 'product_cat',
				'field_type' => 'multi_select',
				'return_format' => 'object',
			),
			array (
				'key' => 'list_load_odd_product_cat',
				'label' => __('Chọn danh mục máy cũ','custom'),
				'name' => 'list_load_odd_product_cat',
				'type' => 'taxonomy',
				'taxonomy' => 'product_cat',
				'field_type' => 'multi_select',
				'return_format' => 'object',
			),
			array (
				'key' => 'tab_phukien_bao_hanh',
				'label' => __('Phụ kiện và bảo hành','custom'),
				'name' => 'tab_phukien_bao_hanh',
				'type' => 'tab',
			),
			array (
				'key' => 'accessories_warranty_title_1',
				'label' => __('Phụ kiện và bảo hành','custom'),
				'name' => 'accessories_warranty_title_1',
				'type' => 'text',
				'default_value' => 'Đủ hộp phụ kiện, bảo hành còn > 9 tháng',
				'placeholder' => __('Nhập Phụ kiện và bảo hành','custom'),
				'wrapper' => array (
					'width' => '70',
				),
			),
			array (
				'key' => 'accessories_warranty_percent_1',
				'label' => __('% giá trị','custom'),
				'name' => 'accessories_warranty_percent_1',
				'type' => 'number',
				'default_value' => '5',
				'min'     => '0',
				'append' => '%',
				'placeholder' => __('Nhập Phụ kiện và bảo hành','custom'),
				'wrapper' => array (
					'width' => '30',
				),
			),
			array (
				'key' => 'accessories_warranty_title_2',
				'label' => __('Phụ kiện và bảo hành','custom'),
				'name' => 'accessories_warranty_title_2',
				'type' => 'text',
				'default_value' => 'Chưa kích hoạt',
				'placeholder' => __('Nhập Phụ kiện và bảo hành','custom'),
				'wrapper' => array (
					'width' => '70',
				),
			),
			array (
				'key' => 'accessories_warranty_percent_2',
				'label' => __('% giá trị','custom'),
				'name' => 'accessories_warranty_percent_2',
				'type' => 'number',
				'default_value' => '10',
				'min'     => '0',
				'append' => '%',
				'placeholder' => __('Nhập Phụ kiện và bảo hành','custom'),
				'wrapper' => array (
					'width' => '30',
				),
			),
			array (
				'key' => 'note_purchase_odd_product',
				'label' => __('Lưu ý','custom'),
				'name' => 'note_purchase_odd_product',
				'type' => 'text',
				'default_value' => 'Lưu ý: Giá thu mua áp dụng cho dung lượng pin > 85% & số lần sạc < 400 lần (ngoài ra giá mua có thể trừ chi phí thay pin).',
			),
			array (
				'key' => 'tab_form_order',
				'label' => __('Form đặt hàng','custom'),
				'name' => 'tab_form_order',
				'type' => 'tab',
			),
			array (
				'key' => 'note_form_order_product',
				'label' => __('Lưu ý','custom'),
				'name' => 'note_form_order_product',
				'type' => 'text',
				'default_value' => '* Quý Khách vui lòng đem máy tới cửa hàng để được định giá và bán lại - lên đời với giá tốt nhất.',
			),
			array(
				'key' => 'list_address_store',
				'label' => __('Danh sách địa chỉ cửa hàng','custom'),
				'name' => 'list_address_store',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => __('Thêm địa chỉ','custom'),
				'sub_fields' => array(
					array (
						'key' => 'address',
						'label' => __('Địa chỉ','custom'),
						'name' => 'address',
						'type' => 'text',
					),
				),
			),
			array (
				'key' => 'tab_success_order',
				'label' => __('Đặt hàng thành công','custom'),
				'name' => 'tab_success_order',
				'type' => 'tab',
			),
			array (
				'key' => 'link_page_order_success',
				'label' => __('Liên kết','custom'),
				'name' => 'link_page_order_success',
				'type' => 'page_link',
				'post_type' => 'page',
				'allow_archives' => 0,
			),
			array (
				'key' => 'title_page_order_success',
				'label' => __('Tiêu đề','custom'),
				'name' => 'title_page_order_success',
				'type' => 'text',
				'default_value' => 'Đặt hàng thành công',
			),
			array (
				'key' => 'descriptiopn_page_order_success',
				'label' => __('Mô tả','custom'),
				'name' => 'descriptiopn_page_order_success',
				'type' => 'wysiwyg',
				'default_value' => '<p>Cảm ơn Quý khách hàng đã chọn mua hàng. Trong <b>15 phút</b>, chúng tôi sẽ <b>SMS hoặc Gọi</b> để xác nhận đơn hàng.</p> <p>* Các đơn hàng từ <b>21h30 tối tới 8h</b> sáng hôm sau, chúng tôi sẽ liên hệ với Quý khách trước 10h trưa cùng ngày.</p> <p>Nếu anh/chị cần tư vấn, trợ giúp vui lòng gọi <b>1900.000</b>. Xin cám ơn !</p>',
			),
			array (
				'key' => 'payment_method',
				'label' => __('Hình thức thanh toán','custom'),
				'name' => 'payment_method',
				'type' => 'text',
				'default_value' => 'Thanh toán khi nhận hàng',
			),
			array (
				'key' => 'payment_status',
				'label' => __('Trạng thái thanh toán','custom'),
				'name' => 'payment_status',
				'type' => 'text',
				'default_value' => 'Chưa thanh toán',
			),
			array (
				'key' => 'payment_note',
				'label' => __('Lưu ý','custom'),
				'name' => 'payment_note',
				'type' => 'text',
				'default_value' => '* Lưu ý: Khuyến mại chỉ được giữ trong vòng 24H kể từ khi đặt hàng. Quá 24H sẽ áp dụng giá mới tại thời điểm đó',
			),
			array (
				'key' => 'tab_email_admin',
				'label' => __('Email','custom'),
				'name' => 'tab_email_admin',
				'type' => 'tab',
			),
			array (
				'key' => 'enable_send_email',
				'label' => __('Kích hoạt nhận mail thông báo','custom'),
				'name' => 'enable_send_email',
				'type' => 'true_false',
				'default_value' => '1',
				'message' => 'Kích hoạt',
			),
			array (
				'key' => 'email_admin_website',
				'label' => __('Email nhận thông báo khi có đơn hàng được gửi','custom'),
				'name' => 'email_admin_website',
				'type' => 'email',
				'default_value' => 'info@phongmy.vn',
				'placeholder' => __('Nhập email nhận thông báo khi có đơn hàng được gửi','custom'),
			),
		),
		'location' => array (
			array (
				array (
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'setting_buy_odd_renew_product',
				),
			),
		),
	));

	// add field for product
	acf_add_local_field_group(array(
		'key'   => 'product_acf_field',
		'title' => __('Thông tin Thu cũ đổi mới sản phẩm','custom'),
		'fields' => array (
			array (
				'key' => 'tab_subsidies',
				'label' => __('Thông tin trợ giá','custom'),
				'name' => 'tab_subsidies',
				'type' => 'tab',
			),
			array (
				'key' => 'title_subsidies',
				'label' => __('Tiêu đề trợ giá','custom'),
				'name' => 'title_subsidies',
				'type' => 'text',
				'placeholder' => __('Nhập tiêu đề trợ giá','custom'),
				'wrapper' => array (
					'width' => '50',
				),
			),
			array (
				'key' => 'max_price_subsidies',
				'label' => __('Tối đa số tiền trợ giá','custom'),
				'name' => 'max_price_subsidies',
				'type' => 'text',
				'placeholder' => __('Nhập Tối đa số tiền trợ giá','custom'),
				'append' => 'đ',
				'wrapper' => array (
					'width' => '50',
				),
			),	
			array (
				'key' => 'price_subsidies',
				'label' => __('Số tiền trợ giá máy mới','custom'),
				'name' => 'price_subsidies',
				'type' => 'number',
				'default_value' => '500000',
				'min'     => '0',
				'append' => 'đ',
			),
			array (
				'key' => 'tab_price',
				'label' => __('Thông tin Giá sản phẩm','custom'),
				'name' => 'tab_price',
				'type' => 'tab',
			),
			array (
				'key' => 'purchase_price',
				'label' => __('Giá máy cũ','custom'),
				'name' => 'purchase_price',
				'type' => 'number',
				'default_value' => '5000000',
				'min'     => '0',
				'append' => 'đ',
			),
			array (
				'key' => 'tab_price_expected',
				'label' => __('Giá dự kiến từ loại sản phẩm','custom'),
				'name' => 'tab_price_expected',
				'type' => 'tab',
			),	
			array (
				'key' => 'type_1_price',
				'label' => __('Giá máy loại 1','custom'),
				'name' => 'type_1_price',
				'type' => 'number',
				'default_value' => '4000000',
				'min'     => '0',
				'append' => 'đ',
			),	
			array (
				'key' => 'type_2_price',
				'label' => __('Giá máy loại 2','custom'),
				'name' => 'type_2_price',
				'type' => 'number',
				'default_value' => '3000000',
				'min'     => '0',
				'append' => 'đ',
			),	
			array (
				'key' => 'type_3_price',
				'label' => __('Giá máy loại 3','custom'),
				'name' => 'type_3_price',
				'type' => 'number',
				'default_value' => '2000000',
				'min'     => '0',
				'append' => 'đ',
			),	
			array (
				'key' => 'type_4_price',
				'label' => __('Giá máy loại 4','custom'),
				'name' => 'type_4_price',
				'type' => 'number',
				'default_value' => '1000000',
				'min'     => '0',
				'append' => 'đ',
			),		
		),
		'location' => array (
			array (
				array (
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'position' => 'normal',
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
// add page "Thu cũ đổi mới" in frontend
if ( is_admin() ){
    $sitemap_page_title = 'Thu cũ đổi mới';
    $sitemap_page_check = get_page_by_title($sitemap_page_title);
    $sitemap_page = array(
	    'post_type'    => 'page',
	    'post_content' => '[purchase_renew_products]',
	    'post_title'   => $sitemap_page_title,
	    'post_status'  => 'publish',
	    'post_author'  => 1,
	    'post_slug'    => 'thu-cu-doi-moi',	    
    );
    if(!isset($sitemap_page_check->ID) && !the_slug_exists('site-map')){
        $sitemap_page_id = wp_insert_post($sitemap_page);
    }
}
// add page "Đặt hàng thành công"
if ( is_admin() ){
    $sitemap_page_title_2 = 'Đặt hàng thành công';
    $sitemap_page_check_2 = get_page_by_title($sitemap_page_title_2);
    $sitemap_page_2 = array(
	    'post_type'    => 'page',
	    'post_content' => '[order_success]',
	    'post_title'   => $sitemap_page_title_2,
	    'post_status'  => 'publish',
	    'post_author'  => 1,
	    'post_slug'    => 'dat-hang-thanh-cong',	    
    );
    if(!isset($sitemap_page_check_2->ID) && !the_slug_exists('site-map-2')){
        $sitemap_page_id_2 = wp_insert_post($sitemap_page_2);
    }
}

// add shortcode view on frontend
function view_purchase_renew_products( $atts, $content = null ){
	ob_start();
		$terms = get_field('list_load_product_cat','option');
		$ids = array();
		foreach( $terms as $term ):
			array_push($ids, $term->term_id);
		endforeach;
		$ids = implode(',', $ids);
		?>
			<?php if( get_field('content_header_page_plugin','option') ): ?>
				<div class="content_header_wrap_thucu">
					<?php echo get_field('content_header_page_plugin','option'); ?>
				</div>
			<?php endif; ?>
			<?php if( $terms ): ?>	
				<div class="thu-cu">			
					<div class="purchase_renew_products">
						<div class="container inner">
							<div class="form-group has-feedback has-search">
								<input type="text" id="inpsearchold" placeholder="<?php echo get_field('title_frm_search_product','option'); ?>" class="form-control" data-id="<?php echo $ids; ?>">
								<span class="form-control-feedback"><i class="fa fa-search"></i></span>
							</div>
							<form class="box-content__box-brand box-content__box-brand_1">
								<?php foreach( $terms as $term ): ?>
									<input type="radio" id="<?php echo $term->slug; ?>" name="optBrandold" value="<?php echo $term->term_id; ?>">
									<label for="<?php echo $term->slug; ?>"><h2><?php echo $term->name; ?></h2></label>
								<?php endforeach; ?>
							</form>
							<div class="wrap_box_list_product"></div>						
						</div>
					</div>
					<div class="loader hidden">
						<!-- <span></span> <span></span> <span></span> -->
						<div class="loading-spin"></div>
					</div>
					<div id="modalThuCu" class="modal modal_class hide fade">
						<div class="modal-dialog modal-lg">
							<div class="modal-content modal_enable">
								<div class="modal-header">
									<button type="button" data-dismiss="modal" class="close">×</button>
									<h4 class="modal-title">TÌNH TRẠNG MÁY CŨ</h4>
								</div>
								<div class="modal-body"></div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" data-toggle="modal" data-target="#modalAlert" class="btn btn-default">Tiếp theo</button>
								</div>
							</div>
						</div>
					</div>
					<div id="modalAlert" class="modal modal_class hide fade">
						<div class="modal-dialog modal-alert">
							<div class="modal-content modal_enable">
								<div class="modal-body"><img src="<?php echo plugins_url('/images/icon_alarm.png', __FILE__); ?>"> <p><strong>Bạn có muốn đổi lên sản phẩm mới không?</strong></p></div> 
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" data-toggle="modal" class="btn btn-default btn_modalListProduct"><strong>Có</strong><br>Muốn đổi mới</button> 
									<button type="button" data-dismiss="modal" data-toggle="modal" class="btn btn-default btn_modalPay"><strong>Không</strong><br>Chỉ muốn bán lại</button>
								</div>
							</div>
						</div>
					</div>
					<div id="modalListProduct" class="modal modal_class hide fade">
						<div class="modal-dialog modal-lg modal-list-product">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" data-dismiss="modal" class="close">×</button> 
									<h4 class="modal-title">CHỌN SẢN PHẨM LÊN ĐỜI</h4>
								</div> 
								<div class="modal-body">
									<form class="box-content__box-brand"></form> 
									<ul class="box-content__list-product"></ul>
								</div>
							</div>
						</div>
					</div>
					<div id="modalPay" class="modal modal_class hide fade">
						<div class="modal-dialog modal-lg modal-pay">
							<div class="modal-content modal_enable">
								<form id="frmThanhToan" method="post" accept-charset="utf-8" enctype="multipart/form-data">
									<div class="modal-header"><button type="button" data-dismiss="modal" class="close">×</button> <h4 class="modal-title"></h4></div>
									<div class="modal-body">
										<div class="modal-body__box-left">
											<img class="product_image"> 
											<p class="product_name">Tên sản phẩm: </p> 
											<div class="box-left__box-price">
												<p>Giá dự kiến thu lại:</p> 
												<p class="box-left__price"></p>
											</div>
										</div> 
										<div class="modal-body__box-right">										
											<div class="form-group col-md-12 col-xs-12">
												<div class="col-md-6 col-xs-12">
													<label for="inptName">Họ và tên: *</label> 
													<input type="text" id="inptName" name="your_name" placeholder="Nhập họ và tên" class="form-control" required>
												</div> 
												<div class="col-md-6 col-xs-12">
													<label for="inptPhone">Số điện thoại: *</label> 
													<input type="tel" id="inptPhone" name="your_phone" placeholder="Nhập số điện thoại!" class="form-control" required>
												</div>
											</div>
											<div class="form-group col-md-12 col-xs-12">
												<div class="col-md-6 col-xs-12">
													<label for="inptEmail">Email: *</label> 
													<input type="email" id="inptEmail" name="your_email" placeholder="Nhập địa chỉ email!" class="form-control" required>
												</div>
												<div class="col-md-6 col-xs-12">
													<label for="select_address">Địa điểm thu máy: *</label> 
													<select name="your_address" id="your_address" class="form-control" required>
														<?php if( have_rows('list_address_store','option') ): ?>
															<?php while( have_rows('list_address_store','option') ) : the_row(); ?>
																<option value="<?php echo get_sub_field('address'); ?>"><?php echo get_sub_field('address'); ?></option>
															<?php endwhile; ?>
														<?php endif; ?>
													</select>
												</div>
											</div> 
											<div class="form-group col-md-12 col-xs-12">
												<div class="col-md-12 col-xs-12">
													<label for="your_message">Ghi chú:</label> 
													<textarea name="your_message" id="your_message" cols="30" rows="10" class="form-control"></textarea>
												</div> 
											</div>
											<input type="hidden" name="your_payment" id="payment-method" value="cashondelivery"> 
											<input type="hidden" name="billing_note" value="[Thu Cũ] - Máy đang dùng: undefined (Loại 1)">
											<input type="hidden" name="product_id">	
											<input type="hidden" name="product_title">	
											<input type="hidden" name="billing_price">	
											<input type="hidden" name="option" value="1">
											<input type="hidden" name="action" value="<?php echo get_field('link_page_order_success','option'); ?>">
											<p class="txt_err" style="display: none;"></p>									
											<p><i><?php echo get_field('note_form_order_product','option'); ?></i></p>
										</div>
									</div> 
									<div class="modal-footer"><button type="submit" class="btn btn-default">Bán ngay</button></div>
								</form>
							</div>
						</div>
					</div>
					<div id="modalPayNew" class="modal modal_class hide fade">
						<div class="modal-dialog modal-lg modal-pay">
							<div class="modal-content modal_enable">
								<form id="frmThanhToanNew" method="post" accept-charset="utf-8" enctype="multipart/form-data">
									<div class="modal-header"><button type="button" data-dismiss="modal" class="close">×</button> <h4 class="modal-title">THÔNG TIN THU CŨ ĐỔI MỚI</h4></div>
									<div class="modal-body">
										<div class="modal-body__box-left">
											<img class="product_image"> 
											<p class="product_name">Tên sản phẩm: </p> 
										</div> 
										<div class="modal-body__box-right">										
											<div class="form-group col-md-12 col-xs-12">
												<div class="col-md-6 col-xs-12">
													<label for="inptNameNew">Họ và tên: *</label> 
													<input type="text" id="inptNameNew" name="your_name" placeholder="Nhập họ và tên" class="form-control" required>
												</div> 
												<div class="col-md-6 col-xs-12">
													<label for="inptPhoneNew">Số điện thoại: *</label> 
													<input type="tel" id="inptPhoneNew" name="your_phone" placeholder="Nhập số điện thoại!" class="form-control" required>
												</div>
											</div>
											<div class="form-group col-md-12 col-xs-12">
												<div class="col-md-6 col-xs-12">
													<label for="inptEmailNew">Email: *</label> 
													<input type="email" id="inptEmailNew" name="your_email" placeholder="Nhập địa chỉ email!" class="form-control" required>
												</div>
												<div class="col-md-6 col-xs-12">
													<label for="select_address">Địa điểm thu máy: *</label> 
													<select name="your_address_new" id="your_address_new" class="form-control" required>
														<?php if( have_rows('list_address_store','option') ): ?>
															<?php while( have_rows('list_address_store','option') ) : the_row(); ?>
																<option value="<?php echo get_sub_field('address'); ?>"><?php echo get_sub_field('address'); ?></option>
															<?php endwhile; ?>
														<?php endif; ?>
													</select>
												</div>
											</div> 
											<div class="form-group col-md-12 col-xs-12">
												<div class="col-md-12 col-xs-12">
													<label for="your_message_new">Ghi chú:</label> 
													<textarea name="your_message_new" id="your_message_new" cols="30" rows="10" class="form-control"></textarea>
												</div> 
											</div>
											<input type="hidden" name="billing_note" value="[Thu Cũ Đổi Mới] - Máy đang dùng: undefined (Loại 1) - Máy lên đời: undefined">
											<input type="hidden" name="product_id">	
											<input type="hidden" name="product_title">
											<input type="hidden" name="old_product_title">	
											<input type="hidden" name="price_new_phone">
											<input type="hidden" name="price_tro_gia">
											<input type="hidden" name="price_old_phone">
											<input type="hidden" name="price">	
											<input type="hidden" name="option" value="1">
											<input type="hidden" name="action" value="<?php echo get_field('link_page_order_success','option'); ?>">	
											<p class="txt_err" style="display: none;"></p>								
											<p><i><?php echo get_field('note_form_order_product','option'); ?></i></p>
										</div>
									</div> 
									<div class="modal-footer"><button type="submit" class="btn btn-default">Bán ngay</button></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if( get_field('content_information_policy','option') || get_field('content_video_policy_plugin','option') || get_field('content_contact_policy_plugin','option') ): ?>
				<div class="thu-cu__box-quy-dinh">
					<div class="container">
						<?php if( get_field('content_information_policy','option') ): ?>
							<div class="box-quy-dinh__box-content">
								<?php echo get_field('content_information_policy','option'); ?>
								<div class="product-left_blog-content_showmore">
									<a href="javascript:;">Xem thêm <i class="fa fa-angle-down"></i></a>
								</div>
							</div>
						<?php endif; ?>
						<?php if( get_field('content_video_policy_plugin','option') ): ?>
							<div class="box-quy-dinh__box-video">
								<?php echo get_field('content_video_policy_plugin','option'); ?>
							</div>
						<?php endif; ?>
						<?php if( get_field('content_contact_policy_plugin','option') ): ?>
							<div class="box-quy-dinh__box-lien-he">
								<?php echo get_field('content_contact_policy_plugin','option'); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php	
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('purchase_renew_products', 'view_purchase_renew_products');

// add ajax load product by cat
function plugin_load_product_by_cat_ajax_handler(){
	$cat     = $_POST['cat'];
    $args = array(
        'posts_per_page' => get_field('number_product_per_page','option'),
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $cat,
	            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<ul class="box-content__list-product">
	    	<?php while ( $products->have_posts() ) : $products->the_post(); global $product; ?>
	    		<?php if( get_field('purchase_price') != '' ): ?>
					<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-toggle="modal">
						<div class="list-product__box-img">
							<?php the_post_thumbnail('original'); ?>
							<?php if( get_field('title_subsidies') || get_field('max_price_subsidies') ): ?>
		    					<div class="box-tro-gia">
		    						<p><?php echo get_field('title_subsidies'); ?> <span><?php echo get_field('max_price_subsidies'); ?> ₫</span></p>
		    					</div>
							<?php endif; ?>
						</div>
						<div class="list-product__box-info"><h3><?php echo get_the_title(); ?></h3></div>
						<div class="list-product__box-price">
							<p>Giá thu cũ: </p> 
							<p class="price">
								<?php 
									if( get_field('purchase_price') ):
										echo number_format(get_field('purchase_price'), 0, ',', '.'); 
									endif;
								?> ₫
							</p>
						</div>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif;

	$args_all = array(
        'posts_per_page' => '-1',
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $cat,
	            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	        ),
	    )
    );
    $products_all = new WP_Query( $args_all );
    if( $products_all->found_posts > get_field('number_product_per_page','option') ):
    	?>
    		<a class="btnShowMore" data-id="<?php echo $cat; ?>" data-offset="<?php echo get_field('number_product_per_page','option'); ?>" data-count="<?php echo $products_all->found_posts; ?>" data-page="1">Xem thêm sản phẩm</a>
    	<?php
    endif;
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_product_data', 'plugin_load_product_by_cat_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_product_data', 'plugin_load_product_by_cat_ajax_handler'); // wp_ajax_nopriv_{action}

// add ajax load more product
function load_more_product_data_handler(){
    $offset = $_POST["offset"];
    $ppp = $_POST["ppp"];
    $cat     = $_POST['cat'];

    $args = array(
        'posts_per_page' => $ppp,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'offset'              => $offset,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $cat,
	            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<?php while ( $products->have_posts() ) : $products->the_post(); global $product; ?>
    		<?php if( get_field('purchase_price') ): ?>
				<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-toggle="modal">
					<div class="list-product__box-img">
						<?php the_post_thumbnail('original'); ?>
						<?php if( get_field('title_subsidies') || get_field('max_price_subsidies') ): ?>
	    					<div class="box-tro-gia">
	    						<p><?php echo get_field('title_subsidies'); ?> <span><?php echo get_field('max_price_subsidies'); ?> ₫</span></p>
	    					</div>
						<?php endif; ?>
					</div>
					<div class="list-product__box-info"><h3><?php echo get_the_title(); ?></h3></div>
					<div class="list-product__box-price">
						<p>Giá thu cũ: </p>
						<p class="price">
							<?php 
								if( get_field('purchase_price') ):
									echo number_format(get_field('purchase_price'), 0, ',', '.'); 
								endif;
							?> ₫
						</p>
					</div>
				</li>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php endif;
    wp_reset_query();
    die; 
}
add_action('wp_ajax_nopriv_load_more_product_data', 'load_more_product_data_handler'); 
add_action('wp_ajax_load_more_product_data', 'load_more_product_data_handler');

// add ajax load_product_data_search by cat
function plugin_load_product_data_search_by_cat_ajax_handler(){
	$ids     = $_POST['ids'];
	$tax_array = explode( ',', $ids );
    $args = array(
    	's'              => $_POST['s'],
        'posts_per_page' => '-1',
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $tax_array,
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<ul class="box-content__list-product">
	    	<?php while ( $products->have_posts() ) : $products->the_post(); global $product; ?>
	    		<?php if( get_field('purchase_price') ): ?>
					<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-toggle="modal">
						<div class="list-product__box-img">
							<?php the_post_thumbnail('original'); ?>
							<?php if( get_field('title_subsidies') || get_field('max_price_subsidies') ): ?>
		    					<div class="box-tro-gia">
		    						<p><?php echo get_field('title_subsidies'); ?> <span><?php echo get_field('max_price_subsidies'); ?> ₫</span></p>
		    					</div>
							<?php endif; ?>
						</div>
						<div class="list-product__box-info"><h3><?php echo get_the_title(); ?></h3></div>
						<div class="list-product__box-price">
							<p>Giá thu cũ: </p>
							<p class="price">
								<?php 
									if( get_field('purchase_price') ):
										echo number_format(get_field('purchase_price'), 0, ',', '.'); 
									endif;
								?> ₫
							</p>
						</div>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif;
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_product_data_search', 'plugin_load_product_data_search_by_cat_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_product_data_search', 'plugin_load_product_data_search_by_cat_ajax_handler'); // wp_ajax_nopriv_{action}

// add load_data_modalThuCu
function plugin_load_data_modalThuCu_ajax_handler(){
	$product = wc_get_product( $_POST['id_product'] );

	$id = $product->get_id();

	$accessories_warranty_1 = round( get_field('purchase_price', $id) * get_field('accessories_warranty_percent_1','option') / 100 );
	$accessories_warranty_2 = round( get_field('purchase_price', $id) * get_field('accessories_warranty_percent_2','option') / 100 );
	?>
		<div class="modal-body__box-left"><img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" /></div>
		<div class="modal-body__box-right">
			<p>Tên sản phẩm: <strong id="product_name_title"><?php echo $product->get_name(); ?></strong></p>
			<p><strong>Ngoại hình máy:</strong></p>
			<form class="box-right__box-option-loai">
				<?php if( get_field('type_1_price', $id) ): ?>
					<input type="radio" id="optLoai1" name="optLoai" value="1" checked="checked" data-title="<?php echo get_the_title($id); ?>" data-price="<?php echo number_format(get_field('type_1_price', $id), 0, ',', '.'); ?> ₫" data-pricenoformat="<?php echo get_field('type_1_price', $id); ?>"> <label for="optLoai1"><strong>Loại 1:</strong> <?php echo get_field('title_product_type_1','option'); ?></label>
				<?php endif; ?>
				<?php if( get_field('type_2_price', $id) ): ?>
					<input type="radio" id="optLoai2" name="optLoai" value="2" data-title="<?php echo get_the_title($id); ?>" data-price="<?php echo number_format(get_field('type_2_price', $id), 0, ',', '.'); ?> ₫" data-pricenoformat="<?php echo get_field('type_2_price', $id); ?>"> <label for="optLoai2"><strong>Loại 2:</strong> <?php echo get_field('title_product_type_2','option'); ?></label> 
				<?php endif; ?>
				<?php if( get_field('type_3_price') ): ?>
					<input type="radio" id="optLoai3" name="optLoai" value="3" data-title="<?php echo get_the_title($id); ?>" data-price="<?php echo number_format(get_field('type_3_price', $id), 0, ',', '.'); ?> ₫" data-pricenoformat="<?php echo get_field('type_3_price', $id); ?>"> <label for="optLoai3"><strong>Loại 3:</strong> <?php echo get_field('title_product_type_3','option'); ?></label> 
				<?php endif; ?>
				<?php if( get_field('type_4_price', $id) ): ?>
					<input type="radio" id="optLoai4" name="optLoai" value="4" data-title="<?php echo get_the_title($id); ?>" data-price="<?php echo number_format(get_field('type_4_price', $id), 0, ',', '.'); ?> ₫" data-pricenoformat="<?php echo get_field('type_4_price', $id); ?>"> <label for="optLoai4"><strong>Loại 4:</strong> <?php echo get_field('title_product_type_4','option'); ?></label>
				<?php endif; ?>
			</form> 
			<p><strong>Phụ kiện và bảo hành:</strong></p> 
			<form class="box-right__box-option-more">
				<input type="radio" id="optMore1" name="optMore" value="<?php echo $accessories_warranty_1; ?>" data-price="<?php echo get_field('type_1_price', $id); ?>"> <label for="optMore1"><span><?php echo get_field('accessories_warranty_title_1','option'); ?></span> <span class="box-right__price">+<?php echo number_format($accessories_warranty_1, 0, ',', '.'); ?> ₫ (<?php echo get_field('accessories_warranty_percent_1','option'); ?>%)</span></label> 
				<input type="radio" id="optMore2" name="optMore" value="<?php echo $accessories_warranty_2; ?>" data-price="<?php echo get_field('type_1_price', $id); ?>"> <label for="optMore2"><span><?php echo get_field('accessories_warranty_title_2','option'); ?></span> <span class="box-right__price">+<?php echo number_format($accessories_warranty_2, 0, ',', '.'); ?> ₫ (<?php echo get_field('accessories_warranty_percent_2','option'); ?>%)</span></label>
				<input type="radio" name="optMore" class="no_option" value="null" style="display:none">
			</form> 
			<p class="expected_price"><strong>Giá dự kiến thu lại <u>loại 1</u>: <span class="box-right__price" data-price="<?php echo get_field('type_1_price', $id); ?>"><?php echo number_format(get_field('type_1_price', $id), 0, ',', '.'); ?> ₫</span></strong></p> 
			<p><i><?php echo get_field('note_purchase_odd_product','option'); ?></i></p>
		</div>
	<?php
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_data_modalThuCu', 'plugin_load_data_modalThuCu_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_data_modalThuCu', 'plugin_load_data_modalThuCu_ajax_handler'); // wp_ajax_nopriv_{action}

// add load_data_modalListProduct
function plugin_load_data_modalListProduct_ajax_handler(){
	$terms = get_field('list_load_new_product_cat','option');
	$ids = array();
	foreach( $terms as $term ):
		array_push($ids, $term->term_id);
	endforeach;
	$ids = implode(',', $ids);
	?>
		<div class="form-group has-feedback has-search">
			<input type="text" id="inpsearchnew" placeholder="Nhập tên sản phẩm bạn muốn định giá!" class="form-control" data-id="<?php echo $ids; ?>" data-price="<?php echo $_POST['price']; ?>"> 
			<span class="form-control-feedback"><i aria-hidden="true" class="fa fa-search"></i></span>
		</div>
		<form class="box-content__box-brand box-content__box-brand_2">
			<?php foreach( $terms as $term ): ?>
				<input type="radio" id="new_<?php echo $term->slug; ?>" name="optBrandnew" value="<?php echo $term->term_id; ?>" data-price="<?php echo $_POST['price']; ?>">
				<label for="new_<?php echo $term->slug; ?>"><h2><?php echo $term->name; ?></h2></label>
			<?php endforeach; ?>
			<input type="radio" id="may-cu" data-price="<?php echo $_POST['price']; ?>">
			<label id="may-cu-label" for="may-cu" class="trade-old-product"><h2>Máy cũ</h2></label>
		</form>
		<div class="wrap_box_list_product_2"></div>
	<?php
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_data_modalListProduct', 'plugin_load_data_modalListProduct_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_data_modalListProduct', 'plugin_load_data_modalListProduct_ajax_handler'); // wp_ajax_nopriv_{action}

// add function load_product_newcat_data
function plugin_load_product_newcat_data_ajax_handler(){
	$cat     = $_POST['cat'];
	$old_price     = $_POST['price'];
    $args = array(
        'posts_per_page' => '-1',
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $cat,
	            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<ul class="box-content__list-product">
	    	<?php while ( $products->have_posts() ) : $products->the_post(); ?>
	    		<?php $product = wc_get_product( get_the_ID() ); ?>
	    		<?php if( $product->get_price() != '' && get_field('price_subsidies') ): ?>
		    		<?php 		    			 
		    			$price_bu = $product->get_price() - get_field('price_subsidies', get_the_ID()) - $old_price;

		    			$price_new_phone = $product->get_price();
		    			$price_tro_gia   = get_field( 'price_subsidies', get_the_ID() );
		    			$price_old_phone = $old_price;
		    			$price           = $price_bu;
	    			?>
					<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-price_new_phone="<?php echo $price_new_phone; ?>" data-price_tro_gia="<?php echo $price_tro_gia; ?>" data-price_old_phone="<?php echo $price_old_phone; ?>" data-price="<?php echo $price; ?>" data-toggle="modal">
						<div class="list-product__box-img">
							<?php the_post_thumbnail('original'); ?>
						</div>
						<div class="list-product__box-info"><h3><?php echo get_the_title(); ?></h3></div>
						<div class="list-product__box-price">
							<div><p>Giá máy:</p> <p class="price_new_phone"><?php echo number_format( $product->get_price(), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Trợ giá:</p> <p class="price_tro_gia"><?php echo number_format( get_field('price_subsidies', get_the_ID() ), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Giá máy cũ:</p> <p class="price_old_phone"><?php echo number_format( $old_price, 0, ',', '.'); ?> ₫</p></div>
							<div><p>Bù chênh lệch:</p> <p class="price"><?php echo number_format( $price_bu, 0, ',', '.'); ?> ₫</p></div>
						</div>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif;
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_product_newcat_data', 'plugin_load_product_newcat_data_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_product_newcat_data', 'plugin_load_product_newcat_data_ajax_handler'); // wp_ajax_nopriv_{action}

// add ajax load_product_newcat_data_search by cat
function plugin_load_product_newcat_data_search_ajax_handler(){
	$ids     = $_POST['ids'];
	$old_price     = $_POST['price'];
	$tax_array = explode( ',', $ids );
    $args = array(
    	's'              => $_POST['s'],
        'posts_per_page' => '-1',
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $tax_array,
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<ul class="box-content__list-product">
	    	<?php while ( $products->have_posts() ) : $products->the_post(); ?>
	    		<?php $product = wc_get_product( get_the_ID() ); ?>
	    		<?php if( $product->get_price() != '' && get_field('price_subsidies') ): ?>
		    		<?php 
		    			$price_bu = $product->get_price() - get_field('price_subsidies', get_the_ID()) - $old_price;

		    			$price_new_phone = $product->get_price();
		    			$price_tro_gia   = get_field( 'price_subsidies', get_the_ID() );
		    			$price_old_phone = $old_price;
		    			$price           = $price_bu;
	    			?>
					<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-price_new_phone="<?php echo $price_new_phone; ?>" data-price_tro_gia="<?php echo $price_tro_gia; ?>" data-price_old_phone="<?php echo $price_old_phone; ?>" data-price="<?php echo $price; ?>" data-toggle="modal">
						<div class="list-product__box-img">
							<?php the_post_thumbnail('original'); ?>
						</div>
						<div class="list-product__box-info"><h3><?php echo get_the_title(); ?></h3></div>
						<div class="list-product__box-price">
							<div><p>Giá máy:</p> <p class="price_new_phone"><?php echo number_format( $product->get_price(), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Trợ giá:</p> <p class="price_tro_gia"><?php echo number_format( get_field('price_subsidies', get_the_ID() ), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Giá máy cũ:</p> <p class="price_old_phone"><?php echo number_format( $old_price, 0, ',', '.'); ?> ₫</p></div>
							<div><p>Bù chênh lệch:</p> <p class="price"><?php echo number_format( $price_bu, 0, ',', '.'); ?> ₫</p></div>
						</div>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif;
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_product_newcat_data_search', 'plugin_load_product_newcat_data_search_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_product_newcat_data_search', 'plugin_load_product_newcat_data_search_ajax_handler'); // wp_ajax_nopriv_{action}

// add ajax load_product_newcat_data_search by cat
function plugin_load_product_oldcat_data_search_ajax_handler(){
	$ids     = $_POST['ids'];
	$old_price     = $_POST['price'];
	$tax_array = explode( ',', $ids );
    $args = array(
    	's'              => $_POST['s'],
        'posts_per_page' => '-1',
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $tax_array,
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<ul class="box-content__list-product">
	    	<?php while ( $products->have_posts() ) : $products->the_post(); ?>
	    		<?php $product = wc_get_product( get_the_ID() ); ?>
	    		<?php if( $product->get_price() != '' && get_field('price_subsidies') ): ?>
		    		<?php 
		    			$price_bu = $product->get_price() - get_field('price_subsidies', get_the_ID()) - $old_price;

		    			$price_new_phone = $product->get_price();
		    			$price_tro_gia   = get_field( 'price_subsidies', get_the_ID() );
		    			$price_old_phone = $old_price;
		    			$price           = $price_bu;
	    			?>
					<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-price_new_phone="<?php echo $price_new_phone; ?>" data-price_tro_gia="<?php echo $price_tro_gia; ?>" data-price_old_phone="<?php echo $price_old_phone; ?>" data-price="<?php echo $price; ?>" data-toggle="modal">
						<div class="list-product__box-img">
							<?php the_post_thumbnail('original'); ?>
						</div>
						<div class="list-product__box-info"><h3>[Máy cũ] <?php echo get_the_title(); ?></h3></div>
						<div class="list-product__box-price">
							<div><p>Giá máy:</p> <p class="price_new_phone"><?php echo number_format( $product->get_price(), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Trợ giá:</p> <p class="price_tro_gia"><?php echo number_format( get_field('price_subsidies', get_the_ID() ), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Giá máy cũ:</p> <p class="price_old_phone"><?php echo number_format( $old_price, 0, ',', '.'); ?> ₫</p></div>
							<div><p>Bù chênh lệch:</p> <p class="price"><?php echo number_format( $price_bu, 0, ',', '.'); ?> ₫</p></div>
						</div>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif;
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_product_oldcat_data_search', 'plugin_load_product_oldcat_data_search_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_product_oldcat_data_search', 'plugin_load_product_oldcat_data_search_ajax_handler'); // wp_ajax_nopriv_{action}

// add load_data_oldcat_modalListProduct
function plugin_load_data_oldcat_modalListProduct_ajax_handler(){
	$terms = get_field('list_load_odd_product_cat','option');
	$ids = array();
	foreach( $terms as $term ):
		array_push($ids, $term->term_id);
	endforeach;
	$ids = implode(',', $ids);
	?>
		<div class="form-group has-feedback has-search">
			<input type="text" id="inpsearchold" placeholder="Nhập tên sản phẩm bạn muốn định giá!" class="form-control" data-id="<?php echo $ids; ?>" data-price="<?php echo $_POST['price']; ?>"> 
			<span class="form-control-feedback"><i aria-hidden="true" class="fa fa-search"></i></span>
		</div>
		<form class="box-content__box-brand box-content__box-brand_2">
			<input type="radio" id="may-moi" data-price="<?php echo $_POST['price']; ?>">
			<label id="may-moi-label" for="may-moi" class="trade-new-product"><h2>Máy mới</h2></label>
			<?php foreach( $terms as $term ): ?>
				<input type="radio" id="new_<?php echo $term->slug; ?>" name="optBrandold" value="<?php echo $term->term_id; ?>" data-price="<?php echo $_POST['price']; ?>">
				<label for="new_<?php echo $term->slug; ?>"><h2><?php echo $term->name; ?></h2></label>
			<?php endforeach; ?>
		</form>
		<div class="wrap_box_list_product_2"></div>
	<?php
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_data_oldcat_modalListProduct', 'plugin_load_data_oldcat_modalListProduct_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_data_oldcat_modalListProduct', 'plugin_load_data_oldcat_modalListProduct_ajax_handler'); // wp_ajax_nopriv_{action}

// add load_data_newcat_modalListProduct
function plugin_load_data_newcat_modalListProduct_ajax_handler(){
	$terms = get_field('list_load_new_product_cat','option');
	$ids = array();
	foreach( $terms as $term ):
		array_push($ids, $term->term_id);
	endforeach;
	$ids = implode(',', $ids);
	?>
		<div class="form-group has-feedback has-search">
			<input type="text" id="inpsearchnew" placeholder="Nhập tên sản phẩm bạn muốn định giá!" class="form-control" data-id="<?php echo $ids; ?>" data-price="<?php echo $_POST['price']; ?>"> 
			<span class="form-control-feedback"><i aria-hidden="true" class="fa fa-search"></i></span>
		</div>
		<form class="box-content__box-brand box-content__box-brand_2">
			<?php foreach( $terms as $term ): ?>
				<input type="radio" id="new_<?php echo $term->slug; ?>" name="optBrandnew" value="<?php echo $term->term_id; ?>" data-price="<?php echo $_POST['price']; ?>">
				<label for="new_<?php echo $term->slug; ?>"><h2><?php echo $term->name; ?></h2></label>
			<?php endforeach; ?>
			<input type="radio" id="may-cu" data-price="<?php echo $_POST['price']; ?>">
			<label id="may-cu-label" for="may-cu" class="trade-old-product"><h2>Máy cũ</h2></label>
		</form>
		<div class="wrap_box_list_product_2"></div>
	<?php
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_data_newcat_modalListProduct', 'plugin_load_data_newcat_modalListProduct_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_data_newcat_modalListProduct', 'plugin_load_data_newcat_modalListProduct_ajax_handler'); // wp_ajax_nopriv_{action}

// add function load_product_oldcat_data
function plugin_load_product_oldcat_data_ajax_handler(){
	$cat     = $_POST['cat'];
	$old_price     = $_POST['price'];
    $args = array(
        'posts_per_page' => '-1',
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'ignore_sticky_posts'   => 1,
        'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $cat,
	            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	        ),
	    )
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>
    	<ul class="box-content__list-product">
	    	<?php while ( $products->have_posts() ) : $products->the_post(); ?>
	    		<?php $product = wc_get_product( get_the_ID() ); ?>
	    		<?php if( $product->get_price() != '' && get_field('price_subsidies') ): ?>
		    		<?php 
		    			$price_bu = $product->get_price() - get_field('price_subsidies', get_the_ID()) - $old_price;

		    			$price_new_phone = $product->get_price();
		    			$price_tro_gia   = get_field( 'price_subsidies', get_the_ID() );
		    			$price_old_phone = $old_price;
		    			$price           = $price_bu;
	    			?>
					<li id="<?php echo get_the_ID(); ?>" data-title="<?php echo get_the_title(); ?>" data-image="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'original' ); ?>" data-price_new_phone="<?php echo $price_new_phone; ?>" data-price_tro_gia="<?php echo $price_tro_gia; ?>" data-price_old_phone="<?php echo $price_old_phone; ?>" data-price="<?php echo $price; ?>" data-toggle="modal">
						<div class="list-product__box-img">
							<?php the_post_thumbnail('original'); ?>
						</div>
						<div class="list-product__box-info"><h3>[Máy cũ] <?php echo get_the_title(); ?></h3></div>
						<div class="list-product__box-price">
							<div><p>Giá máy:</p> <p class="price_new_phone"><?php echo number_format( $product->get_price(), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Trợ giá:</p> <p class="price_tro_gia"><?php echo number_format( get_field('price_subsidies', get_the_ID() ), 0, ',', '.'); ?> ₫</p></div>
							<div><p>Giá máy cũ:</p> <p class="price_old_phone"><?php echo number_format( $old_price, 0, ',', '.'); ?> ₫</p></div>
							<div><p>Bù chênh lệch:</p> <p class="price"><?php echo number_format( $price_bu, 0, ',', '.'); ?> ₫</p></div>
						</div>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif;
    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_load_product_oldcat_data', 'plugin_load_product_oldcat_data_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_load_product_oldcat_data', 'plugin_load_product_oldcat_data_ajax_handler'); // wp_ajax_nopriv_{action}

// add excute_save_information_product
function plugin_excute_save_information_product_handler(){
	$order = array(
	  	'post_title'    => 'Thông tin thu cũ sản phẩm của khách hàng '.$_POST['your_name'].' - '.$_POST['your_phone'],
	  	'post_status'   => 'publish',		  	
		'post_type'     => 'order_product',
		'meta_input'    => array(
			'customer_name'  => $_POST['your_name'],
		    'customer_phone' => $_POST['your_phone'],
		    'customer_email' => $_POST['your_email'],
		    'customer_address' => $_POST['your_address'],
		    'customer_message' => $_POST['your_message'],
		    'customer_note'  => $_POST['customer_note'],

		    'product_id'    => $_POST['product_id'],
		    'new_product_name' => $_POST['product_title'],
		    'odd_product_price' => $_POST['billing_price'],
		),
	);

	$order_id = wp_insert_post( $order );

	if( $order_id ):

		// send email thong bao
		
		$email_ad = get_field('email_admin_website','option');

		$email = get_post_meta( $order_id, 'customer_email', true );

		$subject = 'Thông tin thu cũ sản phẩm của khách hàng '.get_post_meta( $order_id, 'customer_name', true ).' - '.get_post_meta( $order_id, 'customer_phone', true );

		$body .= '<h3>Thông tin đặt hàng</h3>';
		$body .= '<p>Số đơn hàng: <b>'.$order_id.'</b></p>';
		$body .= '<p>Khách hàng: <b>'.get_post_meta( $order_id, 'customer_name', true ).'</b></p>';
		$body .= '<p>Số điện thoại: <b><a href="tel:'.get_post_meta( $order_id, 'customer_phone', true ).'">'.get_post_meta( $order_id, 'customer_phone', true ).'</a></b></p>';		
		$body .= '<p>Email: <b><a href="mailto:'.get_post_meta( $order_id, 'customer_email', true ).'">'.get_post_meta( $order_id, 'customer_email', true ).'</a></b></p>';
		$body .= '<p>Địa điểm thu máy: <b>'.get_post_meta( $order_id, 'customer_address', true ).'</b></p>';
		$body .= '<p>Tin nhắn: <b>'.get_post_meta( $order_id, 'customer_message', true ).'</b></p>';
		$body .= '<p>Ghi chú từ người mua: <b>'.get_post_meta( $order_id, 'customer_note', true ).'</b></p>';

		$body .= '<p>Hình thức thanh toán: <b>'.get_field('payment_method','option').'</b></p>';
		$body .= '<p>Trạng thái thanh toán: <b>'.get_field('payment_status','option').'</b></p>';

		$body .= '<h3>Sản phẩm đã đặt</h3>';
		$body .= '<p>Tên sản phẩm: <b>'.get_post_meta( $order_id, 'new_product_name', true ).'</b></p>';
		$body .= '<p>Số lượng: 1</p>';
		$body .= '<p>Tổng tiền phải thanh toán: <b>'.number_format(get_post_meta( $order_id, 'odd_product_price', true ), 0, ',', '.').' ₫</b></p>';

		$headers = array('Content-Type: text/html; charset=UTF-8');
    	wp_mail( $email, $subject, $body, $headers );

		$headers = array('Content-Type: text/html; charset=UTF-8');
		if( get_field('enable_send_email','option') == 1 ):
		    wp_mail( $email_ad, $subject, $body, $headers );
		    wp_mail( $email, $subject, $body, $headers );
	    endif;

	    // returnn id order
	    echo $order_id;
	endif;

    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_excute_save_information_product', 'plugin_excute_save_information_product_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_excute_save_information_product', 'plugin_excute_save_information_product_handler'); // wp_ajax_nopriv_{action}

// add excute_save_information_product_new
function plugin_excute_save_information_product_new_handler(){
	$order = array(
	  	'post_title'    => 'Thông tin thu cũ đổi mới sản phẩm của khách hàng '.$_POST['your_name'].' - '.$_POST['your_phone'],
	  	'post_status'   => 'publish',		  	
		'post_type'     => 'order_product',
		'meta_input'    => array(
			'customer_name'  => $_POST['your_name'],
		    'customer_phone' => $_POST['your_phone'],
		    'customer_email' => $_POST['your_email'],
		    'customer_address' => $_POST['your_address'],
		    'customer_message' => $_POST['your_message'],
		    'customer_note'  => $_POST['customer_note'],

		    'product_id'    => $_POST['product_id'],
		    'new_product_name' => $_POST['product_title'],
		    'new_product_price' => $_POST['price_new_phone'],
		    'subsidy_product_price' => $_POST['price_tro_gia'],
		    'odd_product_price' => $_POST['price_old_phone'],
		    'compensation_price' => $_POST['price'],
		),
	);

	$order_id = wp_insert_post( $order );

	if( $order_id ):

		// send email thong bao
		
		$email_ad = get_field('email_admin_website','option');

		$email = get_post_meta( $order_id, 'customer_email', true );

		$subject = 'Thông tin thu cũ đổi mới sản phẩm của khách hàng '.get_post_meta( $order_id, 'customer_name', true ).' - '.get_post_meta( $order_id, 'customer_phone', true );

		$body .= '<h3>Thông tin đặt hàng</h3>';
		$body .= '<p>Số đơn hàng: <b>'.$order_id.'</b></p>';
		$body .= '<p>Khách hàng: <b>'.get_post_meta( $order_id, 'customer_name', true ).'</b></p>';
		$body .= '<p>Số điện thoại: <b><a href="tel:'.get_post_meta( $order_id, 'customer_phone', true ).'">'.get_post_meta( $order_id, 'customer_phone', true ).'</a></b></p>';
		$body .= '<p>Email: <b><a href="mailto:'.get_post_meta( $order_id, 'customer_email', true ).'">'.get_post_meta( $order_id, 'customer_email', true ).'</a></b></p>';
		$body .= '<p>Địa điểm thu máy: <b>'.get_post_meta( $order_id, 'customer_address', true ).'</b></p>';
		$body .= '<p>Tin nhắn: <b>'.get_post_meta( $order_id, 'customer_message', true ).'</b></p>';
		$body .= '<p>Ghi chú từ người mua: <b>'.get_post_meta( $order_id, 'customer_note', true ).'</b></p>';

		$body .= '<p>Hình thức thanh toán: <b>'.get_field('payment_method','option').'</b></p>';
		$body .= '<p>Trạng thái thanh toán: <b>'.get_field('payment_status','option').'</b></p>';

		$body .= '<h3>Sản phẩm đã đặt</h3>';
		$body .= '<p>Tên sản phẩm: <b>'.get_post_meta( $order_id, 'new_product_name', true ).'</b></p>';
		$body .= '<p>Số lượng: 1</p>';
		$body .= '<p>Giá máy mới: <b>'.number_format( get_post_meta( $order_id, 'new_product_price', true ), 0, ',', '.' ).' ₫</b></p>';
		$body .= '<p>Trợ giá: <b>'.number_format( get_post_meta( $order_id, 'subsidy_product_price', true ), 0, ',', '.' ).' ₫</b></p>';
		$body .= '<p>Giá máy cũ thu lại: <b>'.number_format( get_post_meta( $order_id, 'odd_product_price', true ), 0, ',', '.' ).' ₫</b></p>';
		$body .= '<p>Giá bù chênh lệch: <b>'.number_format( get_post_meta( $order_id, 'compensation_price', true ), 0, ',', '.' ).' ₫</b></p>';
		$body .= '<p>Tổng tiền phải thanh toán: <b>'.number_format(get_post_meta( $order_id, 'compensation_price', true ), 0, ',', '.').' ₫</b></p>';

		$headers = array('Content-Type: text/html; charset=UTF-8');
    	wp_mail( $email, $subject, $body, $headers );

		$headers = array('Content-Type: text/html; charset=UTF-8');
		if( get_field('enable_send_email','option') == 1 ):
		    wp_mail( $email_ad, $subject, $body, $headers );
		    wp_mail( $email, $subject, $body, $headers );
	    endif;

	    // returnn id order
	    echo $order_id;
	endif;

    wp_reset_query();
    die; // here we exit the script and even no wp_reset_query() required!
} 
add_action('wp_ajax_excute_save_information_product_new', 'plugin_excute_save_information_product_new_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_excute_save_information_product_new', 'plugin_excute_save_information_product_new_handler'); // wp_ajax_nopriv_{action}

// add shortcode order_success 
function shortcode_order_success() {
	ob_start();	
		if( $_GET['ID'] ):
			$args = array( 'post_type' => 'order_product', 'p' => $_GET['ID'] );
			$loop = new WP_Query( $args );
			if( $loop->have_posts() ):
				while ( $loop->have_posts() ) : $loop->the_post();
					$order_id = get_the_ID();
					?>
						<div class="wrap_success_page">
							<div class="inner">
								<div class="success-wrap">
									<div class="left">		
										<a href="<?php echo site_url(); ?>"><i class="fa fa-chevron-left"></i> Tiếp tục tìm kiếm sản phẩm</a>
									</div>
									<div class="clear"></div>
								</div>
								<div class="module">
									<h1><?php echo get_field('title_page_order_success','option'); ?></h1>
								</div>
								<div class="module content_order">
									<div class="decription">
										<?php echo get_field('descriptiopn_page_order_success','option'); ?>
									</div>
									<span><b>Thông tin đặt hàng</b></span>
									<div class="customer_info">
										<p>Số đơn hàng: <b><?php echo get_the_ID(); ?></b></p>
										<p>Khách hàng: <b><?php echo get_post_meta( $order_id, 'customer_name', true ); ?></b></p>
										<p>Số điện thoại: <b><a href="tel:<?php echo get_post_meta( $order_id, 'customer_phone', true ); ?>"><?php echo get_post_meta( $order_id, 'customer_phone', true ); ?></a></b></p>
										<p>Email: <b><a href="mailto:<?php echo get_post_meta( $order_id, 'customer_email', true ); ?>"><?php echo get_post_meta( $order_id, 'customer_email', true ); ?></a></b></p>
										<p>Địa điểm thu máy: <b><?php echo get_post_meta( $order_id, 'customer_address', true ); ?></b></p>
										<p>Tin nhắn: <b><?php echo get_post_meta( $order_id, 'customer_message', true ); ?></b></p>
										<p>Ghi chú từ người mua: <b><?php echo get_post_meta( $order_id, 'customer_note', true ); ?></b></p>
										<div class="space"></div>
										<p>Hình thức thanh toán: <b><?php echo get_field('payment_method','option'); ?></b></p>
										<p>Trạng thái thanh toán: <b><?php echo get_field('payment_status','option'); ?></b></p>
										<p class="note"><?php echo get_field('payment_note','option'); ?></p>
									</div>	
									<span class="wrap_title">Sản phẩm đã đặt</span>
									<div class="module content_product">
										<div class="box_product">
											<div class="left">
												<a href="<?php the_permalink(); ?>">
													<?php $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post_meta( $order_id, 'product_id', true ) ) ); ?>
													<?php if( $featured_image ): ?>
														<img src="<?php echo $featured_image[0]; ?>">
													<?php endif; ?>
												</a>
											</div>
											<div class="right">
												<p><b><?php echo get_post_meta( $order_id, 'new_product_name', true ); ?></b></p>
												<?php if( get_post_meta( $order_id, 'new_product_price', true ) ): ?>
													<p>
														<span class="price-box">
									                        <span class="regular-price">
									                            Giá máy mới: <span class="price"><?php echo number_format( get_post_meta( $order_id, 'new_product_price', true ), 0, ',', '.'); ?> ₫</span>
									                        </span>
									                    </span>
								                    </p>
								                    <p>
														<span class="price-box">
									                        <span class="regular-price">
									                            Trợ giá: <span class="price"><?php echo number_format( get_post_meta( $order_id, 'subsidy_product_price', true ), 0, ',', '.'); ?> ₫</span>
									                        </span>
									                    </span>
								                    </p>
								                    <p>
														<span class="price-box">
									                        <span class="regular-price">
									                            Giá máy cũ thu lại: <span class="price"><?php echo number_format( get_post_meta( $order_id, 'odd_product_price', true ), 0, ',', '.'); ?> ₫</span>
									                        </span>
									                    </span>
								                    </p>
								                    <p>
														<span class="price-box">
									                        <span class="regular-price">
									                            Giá bù chênh lệch: <span class="price"><?php echo number_format( get_post_meta( $order_id, 'compensation_price', true ), 0, ',', '.'); ?> ₫</span>
									                        </span>
									                    </span>
								                    </p>
												<?php else: ?>
													<p>
														<span class="price-box">
									                        <span class="regular-price">
									                            Giá dự kiến thu lại: <span class="price"><?php echo number_format( get_post_meta( $order_id, 'odd_product_price', true ), 0, ',', '.'); ?> ₫</span>
									                        </span>
									                    </span>
								                    </p>
												<?php endif; ?>
												<p>Số lượng: 1</p>
											</div>
											<div class="clear"></div>
										</div>
										<div class="clear"></div>
										<div class="total_price">
											<div class="left">Tổng tiền phải thanh toán</div>
											<div class="right">					                    
							                    <?php if( get_post_meta( $order_id, 'new_product_price', true ) ): ?>
													<span class="price-box">
								                        <span class="regular-price">
								                            <span class="price"><?php echo number_format( get_post_meta( $order_id, 'compensation_price', true ), 0, ',', '.'); ?> ₫</span>
								                        </span>
								                    </span>
												<?php else: ?>
													<span class="price-box">
								                        <span class="regular-price">
								                            <span class="price"><?php echo number_format( get_post_meta( $order_id, 'odd_product_price', true ), 0, ',', '.'); ?> ₫</span>
								                        </span>
								                    </span>
												<?php endif; ?>
							                </div>
							                <div class="clear"></div>
										</div>	
									</div>
									<div class="module module_btn_home">
										<a href="<?php echo site_url(); ?>" class="btn btn-default">Quay lại trang chủ</a>
									</div>							
								</div>								
							</div>
						</div>
					<?php
				endwhile;
			endif;
		endif;
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'order_success', 'shortcode_order_success' );