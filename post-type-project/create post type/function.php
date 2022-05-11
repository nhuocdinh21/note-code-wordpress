<?php
/**
 * Plugin Name: Bất động sản
 * Plugin URI: vinahost.vn
 * Description: Bất động sản
 * Version: 1.0 
 * Author: vinahost
 * Author URI: vinahost.vn
 * License: GPLv2 or later 
 */

//Thêm custom post tài liệu
// Our custom post type function
function wpdocs_codex_bds_init() {
    $labels = array(
        'name'                  => __( 'Dự án', 'custom' ),
        'singular_name'         => __( 'Dự án', 'custom' ),
        'menu_name'             => __( 'Dự án', 'custom' ),
        'name_admin_bar'        => __( 'Dự án', 'custom' ),
        'add_new'               => __( 'Thêm mới', 'custom' ),
        'add_new_item'          => __( 'Thêm mới', 'custom' ),
        'new_item'              => __( 'Thêm mới', 'flatsome' ),
        'edit_item'             => __( 'Sửa dự án', 'flatsome' ),
        'view_item'             => __( 'Xem dự án', 'flatsome' ),
        'all_items'             => __( 'Dự án', 'custom' ),
        'search_items'          => __( 'Tìm kiếm ', 'flatsome' ),
        'parent_item_colon'     => __( 'Parent:', 'flatsome' ),
        'not_found'             => __( 'No found.', 'flatsome' ),
        'not_found_in_trash'    => __( 'No found in Trash.', 'flatsome' ),
        'archives'              => _x( 'app archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'flatsome' ),
        'insert_into_item'      => _x( 'Insert into app', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'flatsome' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this app', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'flatsome' ),
        'filter_items_list'     => _x( 'Filter Apps list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'flatsome' ),
        'items_list_navigation' => _x( 'Apps list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'flatsome' ),
        'items_list'            => _x( 'Apps list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'flatsome' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'du-an' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-building', 
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
    );
 
    register_post_type( 'du-an', $args );
}
 
add_action( 'init', 'wpdocs_codex_bds_init' );
// hook into the init action and call doc_taxonomies when it fires
add_action( 'init', 'bds_taxonomies', 0 );

// create two taxonomies, Categories and writers for the post type "app"
function bds_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Danh mục dự án', 'taxonomy general name', 'flatsome' ),
		'singular_name'     => _x( 'Danh mục dự án', 'taxonomy singular name', 'flatsome' ),
		'search_items'      => __( 'Tìm kiếm danh mục', 'flatsome' ),
		'all_items'         => __( 'Tất cả danh mục', 'flatsome' ),
		'parent_item'       => __( 'Danh mục cha', 'flatsome' ),
		'parent_item_colon' => __( 'Danh mục cha:', 'flatsome' ),
		'edit_item'         => __( 'Sửa danh mục', 'flatsome' ),
		'update_item'       => __( 'Cập nhật', 'flatsome' ),
		'add_new_item'      => __( 'Thêm mới', 'flatsome' ),
		'new_item_name'     => __( 'Tên danh mục', 'flatsome' ),
		'menu_name'         => __( 'Danh mục dự án', 'flatsome' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'danh-muc' ),
	);

	register_taxonomy( 'danh-muc', array( 'du-an' ), $args );
	
}

// Loc danh muc post type
function restrict_books_by_genre() {
    global $typenow;
    $post_type = 'du-an'; // thay doi   
    $taxonomy = 'danh-muc'; // thay doi    
if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Hiển thị {$info_taxonomy->label}"),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => false,
            'hierarchical'      => true,
        ));
    };
}

add_action('restrict_manage_posts', 'restrict_books_by_genre');

function convert_id_to_term_in_query($query) {
    global $pagenow;
    $post_type = 'du-an'; // thay doi 
    $taxonomy = 'danh-muc'; // thay doi 
    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}
add_filter('parse_query', 'convert_id_to_term_in_query'); 

// The Event Location Metabox

/**
 * Add bds custom fields
 */
function add_bds_meta_boxes() {
	add_meta_box("bds_info_meta", "Thông tin bất động sản", "add_contact_details_bds_meta_box", "du-an", "after_title", "high");
}
function edit_form_after_title() {
    // get globals vars
    global $post, $wp_meta_boxes;

    do_meta_boxes( get_current_screen(), 'after_title', $post );

    // unset 'ai_after_title' context from the post's meta boxes
    unset( $wp_meta_boxes['post']['after_title'] );
}
add_action( 'edit_form_after_title', 'edit_form_after_title' );

function add_contact_details_bds_meta_box()
{
	global $post;
	global $wpdb;
	$city = $wpdb->get_results( "SELECT * FROM province" );
	$custom = get_post_custom( $post->ID );
	?>
	<style>
		#bds_info_meta
		{
			margin-top: 15px;
		}
		.cus-row
		{
			width: 100%;
			-js-display: flex;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-orient: horizontal;
			-webkit-box-direction: normal;
			-ms-flex-flow: row wrap;
			flex-flow: row wrap;
		    align-items: center;
		}
		.cus-row p
		{
			width: 100%;
			-js-display: flex;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-orient: horizontal;
			-webkit-box-direction: normal;
			-ms-flex-flow: row wrap;
			flex-flow: row wrap;
		    margin: 10px 0;
		}
		.cus-row p.width50
		{
			max-width: 50%;
		    -ms-flex-preferred-size: 50%;
		    flex-basis: 50%;
		}
		.cus-row .message
		{
			max-width: 100%;
			-ms-flex-preferred-size: 100%;
			flex-basis: 100%;
			font-weight: 600;
			margin: 15px 0 0;
		}
		.cus-row p label
		{
			max-width: 150px;
		    -ms-flex-preferred-size: 150px;
		    flex-basis: 150px;
		}
		.cus-row p select, .cus-row p input
		{
			width: 60%;
			margin: 0;
			min-height: 35px;
			height: 35px;
			line-height: 35px;
			box-shadow: none;
			padding-left: 10px;
		}
		.cus-row p #address
		{
			flex: 1;
		}
		.cus-row p #label_project
		{
			flex: 1;
		}
		.cus-row:after
		{
			content: '';
			display: table;
			clear: both;
		}
		.cus-row .input_wrapper
		{
			display: block;
    		width: 60%;
    		-webkit-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    box-sizing: border-box;
		    position: relative;
		    vertical-align: top;
		}
		.cus-row .input_wrapper .input_inner
		{
			display: block;
			overflow: hidden;
		}
		.cus-row .input_wrapper input
		{
			width: 100%;
			position: relative;
			overflow: hidden;
			border-right: none;
			border-top-right-radius: 0;
			border-bottom-right-radius: 0;
		}
		.cus-row .input_wrapper .input_append
		{
			float: right;
			border-left-width: 0;
			border-radius: 0 3px 3px 0;
			font-size: 14px;
			padding: 0px 8px;
			background: #f5f5f5;
			border: #7e8993 solid 1px;
			min-height: 33px;
			line-height: 30px;	
		}
		.cus-row .input_radio
		{
			line-height: 1;
		}		
		.cus-row .input_radio input
		{
			width: 16px;
			min-height: 16px;
			height: 16px;
			line-height: unset;
			min-width: unset;
			margin: 0 5px 0 0;
			vertical-align: middle;
		}
		.cus-row .group_check .group + .group
		{
			margin-left: 10px;
		}
		.cus-row .group_check .group
		{
			line-height: 1;
			vertical-align: middle;
		}
		.cus-row .group_check input
		{
			width: 16px;
			min-height: 16px;
			height: 16px;
			line-height: unset;
			min-width: unset;
			margin: 0;
			vertical-align: middle;
		}
		@media (max-width: 549px)
		{
			.cus-row p.width50
			{
				max-width: 100%;
			    -ms-flex-preferred-size: 100%;
			    flex-basis: 100%;
			}
			.cus-row p + p
			{
				margin-top: 10px;
			}
			.cus-row p label
			{
				margin: 0 0 10px;
				float: unset;
				width: 30%;
			}
			.cus-row p select, .cus-row p input
			{
				padding: 0 0 0 5px;
				max-width: 100%;
			    -ms-flex-preferred-size: 100%;
			    flex-basis: 100%;
			}
			.cus-row p #address
			{
				flex: unset;
				width: 100%;
			}
		}
		@media (min-width: 550px) and (max-width: 849px)
		{
			.cus-row p
			{

			}
			.cus-row p label
			{
				margin: 0 0 10px;
				max-width: 100px;
				-ms-flex-preferred-size: 100px;
				flex-basis: 100px;
			}
			.cus-row p select, .cus-row p input
			{
				padding: 0 0 0 5px;
				max-width: 95%;
				-ms-flex-preferred-size: 95%;
				flex-basis: 95%;
			}
			.cus-row .input_wrapper input
			{
				max-width: 100%;
				-ms-flex-preferred-size: 100%;
				flex-basis: 100%;
			}
		}
	</style>
	<div class="cus-row">
		<p class="width100">
			<label for="project_type">Loại hình</label>
			<span class="group_check">
				<span class="group">
					<input type="radio" name="project_type" value="thue" id="type_thue" <?php if( @$custom["project_type"][0] == 'thue' ) echo 'checked'; ?>>
					<label for="type_thue">Cho thuê</label>
				</span>
				<span class="group">
					<input type="radio" name="project_type" value="ban" id="type_ban" <?php if( @$custom["project_type"][0] == 'ban' ) echo 'checked'; ?>>
					<label for="type_ban">Bán</label>
				</span>
			</span>
		</p>
		<p class="width50">
			<label for="price">Giá</label>
			<span class="input_wrapper">
				<span class="input_append">vnđ</span>
				<span class="input_inner">
					<input class="width50" type="number" name="price" min="0" value="<?php echo @$custom["price"][0]; ?>" />
				</span>				
			</span>			
		</p>
		<p class="width50 input_radio">
			<input type="checkbox" name="price_negotiate" id="price_negotiate" <?php if( @$custom["price_negotiate"][0] == '1' ) echo 'checked'; ?>>	
			<label for="price_negotiate">Thương lượng</label>	
		</p>
		<p class="width50">
			<label for="city">Tỉnh/thành phố:</label>
			<select name="city" id="city" dis="<?php echo @$custom["district"][0]; ?>">
				<option value="0">-- Chọn tỉnh/thành phố --</option>
				<?php
					foreach ($city as $item) {
						?>
						<option <?php selected( @$custom["city"][0], $item->provinceid ); ?> value='<?php echo $item->provinceid; ?>'><?php echo $item->name; ?></option>
				<?php
					}
				?>
			</select>
		</p>
		<p class="width50">
			<label for="">Quận/Huyện</label>
			<select name="district" id="district" war="<?php echo @$custom["ward"][0]; ?>" str="<?php echo @$custom["street"][0]; ?>">
				<option value="">-- Chọn quận/huyện --</option>
			</select>
			<input type="hidden" id="in_district" value="<?php echo @$custom["district"][0]; ?>">
		</p>
		<p class="width50">
			<label for="">Phường/Xã</label>
			<select name="ward" id="ward">
				<option value="">-- Chọn phường/xã --</option>
			</select>
		</p>
		<p class="width50">
			<label for="">Đường/Phố</label>
			<select name="street" id="street">
				<option value="">-- Chọn đường/phố --</option>
			</select>
		</p>
		<p class="width50">
			<label for="address">Số nhà:</label>
			<input type="text" name="address" value="<?php echo @$custom["address"][0]; ?>" />
		</p>
		<p class="width50">
			<label for="acreage">Diện tích</label>
			<span class="input_wrapper">
				<span class="input_append">m²</span>
				<span class="input_inner">
					<input class="width50" type="number" name="acreage" min="1" value="<?php echo @$custom["acreage"][0]; ?>" />
				</span>				
			</span>
		</p>
		<div class="message">
			<p class="title">Thông tin liên lạc</p>
		</div>		
		<p class="width100">
			<label for="user_name">Tên liên lạc</label>
			<input class="width50" type="text" name="user_name" value="<?php echo @$custom["user_name"][0]; ?>" />
		</p>
		<p class="width50">
			<label for="user_phone">Điện thoại</label>
			<input class="width50" type="text" name="user_phone" value="<?php echo @$custom["user_phone"][0]; ?>" />
		</p>
		<p class="width50 input_radio">
			<input type="checkbox" name="agency" value="1" id="agency" <?php if( @$custom["agency"][0] == 1 ) echo 'checked'; ?>>	
			<label for="agency">Môi giới</label>	
		</p>
		<p class="width100">
			<label for="time_slot">Khung giờ liên lạc tiện nhất</label>
			<span class="group_check">
				<span class="group">
					<input type="radio" name="time_slot" value="1" id="bat_ky" <?php if( @$custom["time_slot"][0] == '1' ) echo 'checked'; ?>>
					<label for="bat_ky">Bất kỳ</label>
				</span>
				<span class="group">
					<input type="radio" name="time_slot" value="2" id="sang" <?php if( @$custom["time_slot"][0] == '2' ) echo 'checked'; ?>>
					<label for="sang">Sáng</label>
				</span>
				<span class="group">
					<input type="radio" name="time_slot" value="3" id="trua" <?php if( @$custom["time_slot"][0] == '3' ) echo 'checked'; ?>>
					<label for="trua">Trưa</label>
				</span>
				<span class="group">
					<input type="radio" name="time_slot" value="4" id="chieu" <?php if( @$custom["time_slot"][0] == '4' ) echo 'checked'; ?>>
					<label for="chieu">Chiều</label>
				</span>
				<span class="group">
					<input type="radio" name="time_slot" value="5" id="toi" <?php if( @$custom["time_slot"][0] == '5' ) echo 'checked'; ?>>
					<label for="toi">Tối</label>
				</span>
			</span>
		</p>
		<p class="width100">
			<label for="total_view">Tổng lượt xem:</label>
			<input type="text" name="total_view" value="<?php echo @$custom["total_view"][0]; ?>" readonly>
		</p>	
	</div>
	<div style="clear: both;"></div>	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			load_quan();
			load_phuong();	
			load_duong();	

			// show district
			$('#city').on('change', function() {
				load_quan();
			});
			function load_quan(){
				var data = {
					'action': 'load_district_ajax',
					'provinceid': $('#city').val()
				};
				$.post(ajaxurl, data, function(rs){
		        	var html='';
		        	var id_curent = $('#city').attr('dis');
		        	$.each(rs, function(i, item) {
		        		if(rs[i].districtid==id_curent){
		        			html+='<option selected="selected" value="'+rs[i].districtid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}else{
		        			html+='<option value="'+rs[i].districtid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}
					})
					$('#district').html(html);
		        });		        
			}

			// show district
			$('#district').on('change', function() {
				load_phuong_1();
				load_duong_1();
			});
			function load_phuong(){
				var data = {
					'action': 'load_ward_ajax',
					'districtid': $('#in_district').val()
				};
				$.post(ajaxurl, data, function(rs){
		        	var html='';
		        	var id_curent = $('#district').attr('war');
		        	$.each(rs, function(i, item) {
		        		if(rs[i].wardid==id_curent){
		        			html+='<option selected="selected" value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}else{
		        			html+='<option value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}
					})
					$('#ward').html(html);
		        });
			}

			function load_phuong_1(){
				var data = {
					'action': 'load_ward_ajax',
					'districtid': $('#district').val()
				};
				$.post(ajaxurl, data, function(rs){
		        	var html='';
		        	var id_curent = $('#district').attr('war');
		        	$.each(rs, function(i, item) {
		        		if(rs[i].wardid==id_curent){
		        			html+='<option selected="selected" value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}else{
		        			html+='<option value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}
					})
					$('#ward').html(html);
		        });
			}

			function load_duong(){
				var data = {
					'action': 'load_street_ajax',
					'districtid': $('#in_district').val()
				};
				$.post(ajaxurl, data, function(rs){
		        	var html='';
		        	var id_curent = $('#district').attr('str');
		        	$.each(rs, function(i, item) {
		        		if(rs[i].streetid==id_curent){
		        			html+='<option selected="selected" value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}else{
		        			html+='<option value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}
					})
					$('#street').html(html);
		        });
			}
			
			function load_duong_1(){
				var data = {
					'action': 'load_street_ajax',
					'districtid': $('#district').val()
				};
				$.post(ajaxurl, data, function(rs){
		        	var html='';
		        	var id_curent = $('#district').attr('str');
		        	$.each(rs, function(i, item) {
		        		if(rs[i].streetid==id_curent){
		        			html+='<option selected="selected" value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}else{
		        			html+='<option value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
		        		}
					})
					$('#street').html(html);
		        });
			}			

		});	
	</script>
	<?php
}
/**
 * Save custom field data when creating/updating posts
 */
function save_bds_custom_fields(){
	global $post;

	if ( $post )
	{	
		if( @$_POST["price_negotiate"] ):
			update_post_meta($post->ID, "price_negotiate", '1');
		else:
			update_post_meta($post->ID, "price_negotiate", '');
		endif;
		
		update_post_meta($post->ID, "project_type", @$_POST["project_type"]);
		update_post_meta($post->ID, "price", @$_POST["price"]);
		update_post_meta($post->ID, "city", @$_POST["city"]);
		update_post_meta($post->ID, "district", @$_POST["district"]);
		update_post_meta($post->ID, "ward", @$_POST["ward"]);
		update_post_meta($post->ID, "street", @$_POST["street"]);
		update_post_meta($post->ID, "address", @$_POST["address"]);
		update_post_meta($post->ID, "acreage", @$_POST["acreage"]);
		update_post_meta($post->ID, "user_name", @$_POST["user_name"]);
		update_post_meta($post->ID, "user_phone", @$_POST["user_phone"]);
		update_post_meta($post->ID, "agency", @$_POST["agency"]);
		update_post_meta($post->ID, "time_slot", @$_POST["time_slot"]);
		update_post_meta($post->ID, "total_view", @$_POST["total_view"]);		
	}
}
add_action( 'admin_init', 'add_bds_meta_boxes' );
add_action( 'save_post', 'save_bds_custom_fields' );

// add load_district_ajax
function load_district_ajax_callback() {
	global $wpdb;
    $district = $wpdb->get_results( "SELECT * FROM district where provinceid=".$_POST['provinceid'] );
    die(wp_send_json($district));
}
add_action('wp_ajax_load_district_ajax', 'load_district_ajax_callback');
add_action('wp_ajax_nopriv_load_district_ajax', 'load_district_ajax_callback');

// add load_ward_ajax
function load_ward_ajax_callback() {
	global $wpdb;
    $ward = $wpdb->get_results( "SELECT * FROM ward where districtid=".$_POST['districtid'] );
    die(wp_send_json($ward));
}
add_action('wp_ajax_load_ward_ajax', 'load_ward_ajax_callback');
add_action('wp_ajax_nopriv_load_ward_ajax', 'load_ward_ajax_callback');

// add load_street_ajax
function load_street_ajax_callback() {
	global $wpdb;
    $street = $wpdb->get_results( "SELECT * FROM street where districtid=".$_POST['districtid'] );
    die(wp_send_json($street));
}
add_action('wp_ajax_load_street_ajax', 'load_street_ajax_callback');
add_action('wp_ajax_nopriv_load_street_ajax', 'load_street_ajax_callback');

// set project view  ------------------------------------------------------------------------------------------------------- 
function getProjectViews($postID){
    $count_key = 'project_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if( $count=='' ){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}
function setProjectViews($postID) {
    $count_key = 'project_views_count';
    $count = get_post_meta($postID, $count_key, true);

    $count_total = get_post_meta($postID, 'total_view', true);
    if( $count == '' ){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);

        $count_total++;
        update_post_meta($postID, 'total_view', $count_total);
    }
}

// add columns featured image
add_filter('manage_du-an_posts_columns', 'vina_columns_project_filter_head');
add_action('manage_du-an_posts_custom_column', 'vina_columns_project_filter_content', 10, 2);

function vina_columns_project_filter_head($columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'id' => __('Mã tin đăng','custom'),
        'title' => __('Tiêu đề','custom'),
        // 'danh_muc' => __('Chuyên mục','custom'),
        'taxonomy-danh-muc' => __('Chuyên mục','custom'),
        'project_author' => __('Người đăng','custom'),
        'project_status' => __('Trạng thái','custom'),
        'project_real_view' => __('Lượt xem thực','custom'),
        'project_manual_view' => __('Lượt xem manual','custom'),
        'project_total_view' => __('Lượt xem tổng','custom'),
        'create_date' => __('Ngày đăng','custom'),
        'publish_date' => __('Ngày duyệt','custom'),
    );
    unset($columns['cb']);
    unset($columns['title']);
    unset($columns['author']);
    unset($columns['taxonomy-danh-muc']);
    unset($columns['date']);
    return $new_columns + $columns; // This way your custom columns are at the end
}
 
function vina_columns_project_filter_content($column_name, $post_ID) {
    global $post;
    if ($column_name == 'id') {       
        echo '#' . get_the_ID();     
    } 
    if ($column_name == 'danh_muc') {       
        $cats = get_the_terms($post_ID, 'danh-muc');
		foreach( $cats as $cat ):
			if( $cat->parent != '0' ):
				$term_id = $cat->parent;
			else:
				$term_id = $cat->term_id;
			endif;											
		endforeach; 

		$term = get_term($term_id, 'danh-muc');
		echo '<a href="'.get_edit_term_link($term->term_id, 'danh-muc').'">'.$term->name.'</a>';  
    } 
    if ($column_name == 'project_author') {       
		$user_id = get_post_field( 'post_author', $post_ID );
		$user_info = get_userdata($user_id);
    	echo $user_info->user_email;
    }  
    if ($column_name == 'project_status') {       
		$post_status = get_post_status($post_ID);
		if( $post_status == 'publish' ):
			echo '<span style="color: #2BAD6E;">' . __('Đã duyệt','custom') . '</span>';
		elseif( $post_status == 'pending'):
			echo '<span style="color: #E9B02C;">' . __('Chưa duyệt','custom') . '</span>';
		else:
			echo '<span>' . __('Bản nháp','custom') . '</span>';
		endif;
    }   
    if ($column_name == 'project_real_view') {      
    	$real_view = getProjectViews($post_ID); 
		echo number_format($real_view, 0, ',', '.');
    }
    if ($column_name == 'project_manual_view') {  
    	$current_user = wp_get_current_user();
    	$allowed_roles = array('editor', 'administrator');
    	if( array_intersect($allowed_roles, $current_user->roles ) ):
    		echo '<input type="number" min="0" name="manual_view" class="input_manual_view" data-attr="total_view" data-view="'.getProjectViews($post_ID).'" data-post="'.$post_ID.'" style="width: 70px;">';
    	endif;    	
    }
    if ($column_name == 'project_total_view') {      
    	$total_view = get_post_field( 'total_view', $post_ID );
    	if( $total_view ):
    		echo number_format($total_view, 0, ',', '.');
    	else:
    		echo '0';
    	endif;		
    }
    if ($column_name == 'create_date') {      
    	echo get_the_date('d/m/Y • H:i');
    }
    if ($column_name == 'publish_date') {      
    	echo get_the_modified_date('d/m/Y • H:i');;
    }
}

// add admin_excute_javascript
add_action( 'admin_footer', 'admin_excute_javascript' );
function admin_excute_javascript() { 
	?>
	    <script>
	        jQuery(function($) {
	        	if( $('.input_manual_view').length > 0 ){
	        		$('.input_manual_view').change(function(e){
	        			e.preventDefault();
	        			var meta_key = $(this).data('attr');
	        			var post_id = $(this).data('post');
	        			var value = $(this).val();
	        			var view = $(this).data('view');
	        			$.ajax({
		                    type: 'POST',
		                    url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
		                    data: ({
		                        action: 'update_total_view',
		                        post_id: post_id,
		                        meta_key: meta_key,
		                        value: value,
		                        view: view,
		                    }),
		                    success: function(data) {
		                        console.log(data);
		                    },
		                    complete: function(){

		                    }
		                });
		                return false;
	        		});
	        	}
	        });
	    </script>
  	<?php
} 

// add update_total_view ajax
function function_update_total_view() {
	$data = array();
	$meta_key = $_POST['meta_key'];
	$post_id = $_POST['post_id'];
	$value = $_POST['value'] + $_POST['view'];
	update_post_meta($post_id, $meta_key, $value);
	// update_post_meta($post_id, 'project_views_count', $value);	
    die();
}
add_action('wp_ajax_update_total_view', 'function_update_total_view');
add_action('wp_ajax_nopriv_update_total_view', 'function_update_total_view');
