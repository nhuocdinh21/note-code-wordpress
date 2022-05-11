<?php
/**
 * Plugin Name: Mini Forum
 * Plugin URI: https://vinahost.vn/
 * Description: Mini Forum Chat
 * Version: 1.0
 * Author: Vinahost - VNHTeam
 * Author URI: https://vinahost.vn/
 * License: GPLv2
 */

if( ! defined( 'ABSPATH' ) ) exit;

define('PLUGIN_URL_2', plugin_dir_url(__FILE__));
define('PLUGIN_PATH', plugin_dir_path(__FILE__));

//add admin css
function admin_style() {
	wp_enqueue_style('mini_forum_admin', plugins_url('/assets/css/admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'admin_style');

// add frontend css
function style_frontend() {
    wp_register_style('main_css', plugins_url('/assets/css/main.css', __FILE__));
    wp_enqueue_style('main_css');

    wp_register_style('magnific_popup_css', plugins_url('/assets/css/magnific-popup.css', __FILE__));
    wp_enqueue_style('magnific_popup_css');    

    wp_register_script('magnific_popup_js', plugins_url( '/assets/js/jquery.magnific-popup.js', __FILE__ ));
    wp_enqueue_script( 'magnific_popup_js' );
    
}
add_action('wp_print_scripts', 'style_frontend');

// add main frontend js
add_action( 'wp_footer', 'add_main_frontend_script' );
function add_main_frontend_script(){
	wp_register_script('main_js', plugins_url( '/assets/js/main.js', __FILE__ ));
    wp_enqueue_script( 'main_js' );

    wp_register_script('validate', plugins_url( '/assets/js/jquery.validate.min.js', __FILE__ ));
    wp_enqueue_script( 'validate' );
}


function js_enqueue_scripts() {
    wp_enqueue_script ("my-ajax-handle", PLUGIN_URL_2 . "assets/js/ajax.js", array('jquery')); 
    //the_ajax_script will use to print admin-ajaxurl in custom ajax.js
    wp_localize_script('my-ajax-handle', 'the_ajax_script', array('ajaxurl' =>admin_url('admin-ajax.php')));
} 
add_action("wp_enqueue_scripts", "js_enqueue_scripts");


// add post type cauhoi 
add_action( 'init', 'create_question_post_type' ); 
function create_question_post_type() {   

	// add post type Cau hoi
    $label_1 = array( 
        'name' => __('Câu hỏi', 'custom'), 
        'add_new' => __('Thêm mới', 'custom'), 
        'add_new_item' => __('Thêm mới','custom'), 
        'edit_item' => __('Sửa câu hỏi','custom'), 
        'new_item' => __('Thêm mới','custom'), 

        'view_item' => __('Xem câu hỏi','custom'), 
        'search_items' => __('Tìm kiếm ','custom'), 
        'not_found' => __('Không tin thấy','custom'), 
        'not_found_in_trash' => __('Không có gì trong Thùng rác','custom'), 
        'parent_item_colon' => '' 
    );   

    $args_1 = array( 
        'labels' => $label_1, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true, 
        'menu_icon' => 'dashicons-admin-comments', 
        'rewrite' => array( 'slug' => 'cauhoi', 'with_front'=> false ), 
        'capability_type' => 'post',
        'hierarchical' => true,
        'has_archive' => false,  
        'menu_position' => 30, 
        'supports' => array('title','editor','author','thumbnail'),
    );
    register_post_type( 'cauhoi' , $args_1 );

    register_taxonomy( 'chude', array('cauhoi'), array(
        'hierarchical' => true, 
        'label' => __('Chủ đề','custom'), 
        'singular_label' => __('Chủ đề','custom'), 
        'rewrite' => array( 'slug' => 'chude', 'with_front'=> false )
        )
    );
    register_taxonomy_for_object_type( 'chude', 'cauhoi' );

    // add post type Tra loi
    $label_2 = array( 
        'name' => __('Trả lời', 'custom'), 
        'add_new' => __('Thêm mới', 'custom'), 
        'add_new_item' => __('Thêm mới','custom'), 
        'edit_item' => __('Sửa câu hỏi','custom'), 
        'new_item' => __('Thêm mới','custom'), 

        'view_item' => __('Xem câu hỏi','custom'), 
        'search_items' => __('Tìm kiếm ','custom'), 
        'not_found' => __('Không tin thấy','custom'), 
        'not_found_in_trash' => __('Không có gì trong Thùng rác','custom'), 
        'parent_item_colon' => '' 
    );   

    $args_2 = array( 
        'labels' => $label_2, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true, 
        'menu_icon' => 'dashicons-admin-comments', 
        'rewrite' => array( 'slug' => 'traloi', 'with_front'=> false ), 
        'capability_type' => 'post',
        'hierarchical' => true,
        'has_archive' => false,  
        'menu_position' => null, 
        'supports' => array('title','author'),
        'show_in_menu'  =>	'edit.php?post_type=cauhoi',
    );
    register_post_type( 'traloi' , $args_2 );
}

// add metabox parent id cau hoi
function add_status_parentid_question() {
	add_meta_box("parentid_question", __('Trả lời cho câu hỏi có ID','custom'), "add_parentid_question_meta_box", "traloi", "side", "high");
}
function add_parentid_question_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
	?>
		<input type="text" id="parentid_question" name="parentid_question" value="<?php echo @$custom["parentid_question"][0]; ?>" readonly>
	<?php
}
// add function save metabox parent id cau hoi
function save_metabox_parentid_question(){
	global $post;
	if ( $post )
	{
		update_post_meta($post->ID, "parentid_question", @$_POST["parentid_question"]);
	}
}
add_action( 'admin_init', 'add_status_parentid_question' );
add_action( 'save_post', 'save_metabox_parentid_question' );

// add page Setting Hoi dap
add_action('admin_menu', 'add_submenu_setting_hoidap');
if(!function_exists('add_submenu_setting_hoidap')){
	function add_submenu_setting_hoidap() {
		add_submenu_page( 'edit.php?post_type=cauhoi', 'Thiết lập', 'Thiết lập', 'manage_options', 'setting-hoidap', 'function_setting_hoidap');
	}
}

// add variable setting page hoi dap
add_action( 'admin_init', 'register_setting_hoidap' );
function register_setting_hoidap(){
    register_setting( 'hoidap_settings', 'title_popup' );
    register_setting( 'hoidap_settings', 'kiemduyet_cauhoi' );
    register_setting( 'hoidap_settings', 'kiemduyet_traloi' );
    register_setting( 'hoidap_settings', 'soluong_cauhoi' );
    register_setting( 'hoidap_settings', 'email_admin' );
    register_setting( 'hoidap_settings', 'email_subject' );
    register_setting( 'hoidap_settings', 'email_content' );
    register_setting( 'hoidap_settings', 'customer_subject' );
    register_setting( 'hoidap_settings', 'customer_content' );
    register_setting( 'hoidap_settings', 'notification_cauhoi' ); 
    register_setting( 'hoidap_settings', 'notification_cautraloi' );
    register_setting( 'hoidap_settings', 'check_emailadmin' );
    register_setting( 'hoidap_settings', 'check_emailcustomer' ); 
    register_setting( 'hoidap_settings', 'site_key' );
    register_setting( 'hoidap_settings', 'secret_key' );         
}

// function_setting_hoidap
function function_setting_hoidap()
{	
	?>
		<div class="wrap_setting">
			<h1><?php echo __('Thiết lập','custom'); ?></h1>
			<hr>
			<form id="frm_hoidap" method="post" action="options.php">
				<?php settings_fields( 'hoidap_settings' ); ?>
				<div class="custom_wrap wrap_popup">
					<h3><?php echo __('Thiết lập Popup Đăng ký / Đăng nhập','custom'); ?></h3>
					<table>
						<tbody>
							<tr>
								<th><?php echo __('Tiêu đề Popup','custom'); ?></th>
								<td><input class="of-input " name="title_popup" id="title_popup" type="text" value="<?php echo get_option('title_popup'); ?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="custom_wrap wrap_cauhoi">
					<h3><?php echo __('Thiết lập cho phần Câu hỏi','custom'); ?></h3>
					<table>
						<tbody>
							<tr>
								<th><?php echo __('Thực hiện chức năng kiểm duyệt không?','custom'); ?></th>
								<td><input type="checkbox" class="checkbox of-input" name="kiemduyet_cauhoi" id="kiemduyet_cauhoi" value="1" <?php checked( '1', get_option( 'kiemduyet_cauhoi' ) ); ?>></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Hiển thị nhiều nhất','custom'); ?></th>
								<td><input type="number" class="checkbox of-input" name="soluong_cauhoi" id="soluong_cauhoi" value="<?php echo get_option('soluong_cauhoi'); ?>" min="1" style="padding: 3px;"> câu hỏi</td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Thông báo khi đăng câu hỏi','custom'); ?></th>
								<td><input class="of-input " name="notification_cauhoi" id="notification_cauhoi" type="text" placeholder="Câu hỏi của bạn đã được gửi. Vui lòng chờ xét duyệt của người quản trị." value="<?php echo get_option('notification_cauhoi'); ?>" style="width: 100%;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="custom_wrap wrap_traloi">
					<h3><?php echo __('Thiết lập cho phần Trả lời','custom'); ?></h3>
					<table>
						<tbody>
							<tr>
								<th><?php echo __('Thực hiện chức năng kiểm duyệt không?','custom'); ?></th>
								<td><input type="checkbox" class="checkbox of-input" name="kiemduyet_traloi" id="kiemduyet_traloi" value="1" <?php checked( '1', get_option( 'kiemduyet_traloi' ) ); ?>></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Thông báo khi đăng câu trả lời','custom'); ?></th>
								<td><input class="of-input " name="notification_cautraloi" id="notification_cautraloi" type="text" placeholder="Câu trả lời đã được gửi. Vui lòng chờ xét duyệt của người quản trị." value="<?php echo get_option('notification_cautraloi'); ?>" style="width: 100%;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="custom_wrap wrap_email">
					<h3><?php echo __('Thiết lập cho phần Gửi email','custom'); ?></h3>
					<table>
						<tbody>
							<tr>
								<th><?php echo __('Gửi thông báo cho người quản trị không?','custom'); ?></th>
								<td><input type="checkbox" class="checkbox of-input" name="check_emailadmin" id="check_emailadmin" style="width: auto;" value="1" <?php checked( '1', get_option( 'check_emailadmin' ) ); ?>></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Email người quản trị','custom'); ?></th>
								<td><input class="of-input " name="email_admin" id="email_admin" type="email" placeholder="example@gmail.com" value="<?php echo get_option('email_admin'); ?>"></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Tiêu đề email gửi cho người quản trị','custom'); ?></th>
								<td><input class="of-input " name="email_subject" id="email_subject" type="text" placeholder="Bạn vừa nhận 1 câu hỏi trên website." value="<?php echo get_option('email_subject'); ?>"></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Nội dung email gửi cho người quản trị','custom'); ?></th>
								<td><textarea name="email_content" id="email_content" placeholder="Có 1 khách hàng đã đặt câu hỏi trên website của bạn. Vui lòng đăng nhập website để xem chi tiết." style="width: 100%; height: 100px;"><?php echo get_option('email_content'); ?></textarea></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Gửi thông báo cho khách hàng không?','custom'); ?></th>
								<td><input type="checkbox" class="checkbox of-input" name="check_emailcustomer" id="check_emailcustomer" style="width: auto;" value="1" <?php checked( '1', get_option( 'check_emailcustomer' ) ); ?>></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Tiêu đề email gửi cho người khách hàng','custom'); ?></th>
								<td><input class="of-input " name="customer_subject" id="customer_subject" type="text" placeholder="Bạn vừa nhận được câu trả lời." value="<?php echo get_option('customer_subject'); ?>"></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Nội dung email gửi cho người khách hàng','custom'); ?></th>
								<td><textarea name="customer_content" id="customer_content" placeholder="Câu hỏi mà bạn đã hỏi đã có câu trả lời. Vui lòng truy cập website để xem chi tiết." style="width: 100%; height: 100px;"><?php echo get_option('customer_content'); ?></textarea></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="custom_wrap wrap_capcha">
					<h3><?php echo __('Thiết lập Google Captcha','custom'); ?></h3>
					<table>
						<tbody>
							<tr>
								<th><?php echo __('Site Key','custom'); ?></th>
								<td><input class="of-input " name="site_key" id="site_key" type="text" placeholder="6LeWOMsUAAAAAH58E0E-7X8Zq1Ft3sKQwVCHBwVT" value="<?php echo get_option('site_key'); ?>"></td>
							</tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr>
								<th><?php echo __('Secret Key','custom'); ?></th>
								<td><input class="of-input " name="secret_key" id="secret_key" type="text" placeholder="6LeWOMsUAAAAACOzVnQVJLdJgygrziCVXciMQNH-" value="<?php echo get_option('secret_key'); ?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="save_setting"> 
					<input type="submit" name="submit" class="button button-primary" value="Lưu thay đổi">
				</div>
			</form>
		</div>
	<?php
}

// setup a function to check if these pages exist
function the_slug_exists($post_name) {
	global $wpdb;
	if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
		return true;
	} else {
		return false;
	}
}

// add page "Hoi dap" in frontend
if ( is_admin() ){
    $sitemap_page_title = 'Hỏi đáp';
    $sitemap_page_check = get_page_by_title($sitemap_page_title);
    $sitemap_page = array(
	    'post_type' => 'page',
	    'post_content' => '[hoidap]',
	    'post_title' => $sitemap_page_title,
	    'post_status' => 'publish',
	    'post_author' => 1,
	    'post_slug' => 'hoi-dap',	    
    );
    if(!isset($sitemap_page_check->ID) && !the_slug_exists('site-map')):
        $sitemap_page_id = wp_insert_post($sitemap_page);
    endif;
}

// add shortcode view on frontend
function view_template_hoidap( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title'	=> '',
	), $atts ) );
	ob_start();
		// check has login
		if( !is_user_logged_in() ):
			add_action('wp_footer', 'add_template_popup_login');
		    function add_template_popup_login(){
		        include( PLUGIN_PATH . 'template_popup.php' );		        
		    }
		endif;
		$user_id = get_current_user_id();
		?>
			<div class="row row-small layout_hoidap">
				<div class="col large-3 hoidap_sidebar">
					<div class="col-inner">						
						<?php 
							$catechild = get_terms( 'chude', array(
							    'orderby'    => 'name',
							    'order'=>'ASC',
							    'hide_empty' => 0,		    
							    'parent'=> 0
							) );
							if( $catechild ): ?>
								<div class="box box_chude">
									<h3 class="title"><?php echo __('Chủ đề','custom'); ?></h3>
									<ul class="ul_box list_chude">
										<?php foreach ($catechild as $catathumti): ?>
											<li onclick="get_question_by_category(<?php echo $catathumti->term_id; ?>)">
												<a title="<?php echo $catathumti->name; ?>"><?php echo $catathumti->name; ?></a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
						<?php endif; ?>
						<?php 
							$args = array(
								'post_type'=> 'cauhoi',
								'posts_per_page' => '5',
							);
							$my_query = new wp_query( $args );
							if( $my_query->have_posts() ) : ?>
								<div class="box box_list_cauhoi">
									<h3 class="title"><?php echo __('Danh sách câu hỏi mới nhất','custom'); ?></h3>
									<ul class="ul_box list_cauhoi">
										<?php while( $my_query->have_posts() ) : $my_query->the_post(); ?>
											<li>
												<a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_content(); ?></a>
											</li>
										<?php endwhile; ?>
									</ul>
								</div>
							<?php
							endif;
							wp_reset_query(); 
						?>
						<?php dynamic_sidebar('hoidap_sidebar'); ?>
					</div>
				</div>
				<div class="col large-9 hoidap_content medium-col-first">
					<div class="col-inner">
						<div class="wrap_frm_datcauhoi">
							<form action="" method="post" id="frm_datcauhoi" class="frm_datcauhoi" enctype="multipart/form-data">
								<div class="social-comment">
									<a href="#" class="pull-left"><span class="image"></span></a>
                                    <div class="social-body">
                                        <ul class="list-inline">
                                            <li>
                                            	<a href="javascript:;" class="active"><span class="fa fa-fw fa-question-circle"></span> Đặt câu hỏi
                                                </a>
                                            </li>
                                            <li>|</li>
                                            <li class=""><a href="javascript:;">Chọn chủ đề</a></li>
                                            <li>
                                                <select name="chude" required="required">
                                                	<option value=""><?php echo __('Chọn chủ đề','custom'); ?></option>
                                                	<?php 
                                                		$catechild = get_terms( 'chude', array(
														    'orderby'    => 'name',
														    'order'=>'ASC',
														    'hide_empty' => 0,		    
														    'parent'=> 0
														) );
														if( $catechild ):
															foreach ($catechild as $catathumti):
																?>
																	<option value="<?php echo $catathumti->term_id; ?>"><?php echo $catathumti->name; ?></option>
																<?php
															endforeach;
														endif;
                                                	?>													
												</select>
                                            </li>
                                        </ul>
                                        <div class="line">
                                            <hr class="">
                                            <hr class="arrow-tip">
                                        </div>
                                    </div>
                                    <div class="media-body form-group">
                                        <textarea required="required" name="question" placeholder="Bạn muốn hỏi về điều gì?" autocomplete="false" class="form-control validate" id="inputquestion"></textarea>
                                        <label for="inputthumb" class="btn-img">Hình ảnh<input multiple="multiple" type="file" id="inputthumb" name="kv_multiple_attachments[]" accept='image/*'></label>
                                     	<div id="q_thumb"><img src=""></div>                                         
                                    </div>
                                </div>
                                <div class="text-right submit-form">
                                    <button class="btn_datcauhoi" type="submit">Gửi <i aria-hidden="true" class="fa fa-fw fa-paper-plane"></i></button>                                    
                                    <input type="hidden" name="option" value="datcauhoi_<?php echo $user_id; ?>" />
                                </div>
							</form>
							<?php if( !is_user_logged_in() ): ?>
								<script type="text/javascript">
									jQuery(function($) {
										$('.btn_datcauhoi').click(function() {
											$.magnificPopup.open({
									    		items: {
									        		src: '#popup_login' 
									    		},
									    		type: 'inline'
									      	});
										});
									});
								</script>
							<?php endif; ?>
						</div>
						<div class="loading-screen" style="display: none;">
                            <div class="timeline-item">
                                <div class="animated-background facebook">
                                    <div class="background-masker header-top-1"></div>
                                    <div class="background-masker header-left"></div>
                                    <div class="background-masker header-right"></div>
                                    <div class="background-masker header-bottom-1"></div>
                                    <div class="background-masker subheader-left"></div>
                                    <div class="background-masker subheader-right"></div>
                                    <div class="background-masker subheader-bottom"></div>
                                    <div class="background-masker content-top"></div>
                                    <div class="background-masker content-first-end"></div>
                                    <div class="background-masker content-second-line"></div>
                                    <div class="background-masker content-second-end"></div>
                                    <div class="background-masker content-third-line"></div>
                                    <div class="background-masker content-third-end"></div>
                                </div>
                            </div>
                        </div>                        
                        <div>
                        	<div id="all_reply"></div>
                        </div>                                              
					</div>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(function($) {
					// add all_reply page hoi dap
					$.ajax({
				        type: "post",
				        url: the_ajax_script.ajaxurl,
				        data: {
				            action: "ajax_load_all_reply",
				        },
				        context: this,
				        beforeSend: function () {
				        },
				        success: function (response) {
				            jQuery("#all_reply").html(response);
				            jQuery(".loading-screen").hide();
				            // console.log(response);
				        },
				        error: function (jqXHR, textStatus, errorThrown) {
				        }
				    })

				});
			</script>
		<?php	
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('hoidap', 'view_template_hoidap');

// function excute submit form dat cau hoi
function excute_submit_form_datcauhoi() {
    $kd_cauhoi = get_option('kiemduyet_cauhoi');
    $user_id = get_current_user_id();
	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['option'] ) &&  $_POST['option'] == 'datcauhoi_'.$user_id ):
		if( $kd_cauhoi == '1' ):
			$status = 'pending';
		else:
			$status = 'publish';
		endif;
		$title = $_POST['question'];
		if( strlen($title) > 50 ) :
			$title_p = substr($title, 0, 50);
		else:
			$title_p = $title;
		endif;
		$my_post = array(
		  	'post_title'    => $title_p,
		  	'post_content' => $_POST['question'],
		  	'post_status'   => $status,		  	
			'post_type' => 'cauhoi',
			'post_author' => $user_id,
			'tax_input'  =>array(
				'chude' => $_POST['chude']
			)									
		);
		$errors = write_here_errors()->get_error_messages();
		if( empty($errors) ):

			$post_id = wp_insert_post( $my_post );
			if ( $_FILES ) 
	   		{
				$filess = $_FILES["kv_multiple_attachments"];
				if($filess)
				{
					foreach ($filess['name'] as $key => $value) { 			
						if ($filess['name'][$key]) { 
							$file = array( 
								'name' => $filess['name'][$key],
			 					'type' => $filess['type'][$key], 
								'tmp_name' => $filess['tmp_name'][$key], 
								'error' => $filess['error'][$key],
		 						'size' => $filess['size'][$key]
							); 
							$_FILES = array ("kv_multiple_attachments" => $file); 
							foreach ($_FILES as $file => $array) {				
								$newupload = kv_handle_attachment($file,$post_id); 
							}
						} 
					}
				}										 
			}
			if( $kd_cauhoi == '1' ):
				if( get_option('notification_cauhoi') ): 
					?>
						<script type="text/javascript">
							alert('<?php echo get_option('notification_cauhoi'); ?>');
							window.location.replace('<?php echo site_url(); ?>');
						</script>
					<?php
				else:
					?>
						<script type="text/javascript">
							alert('Câu hỏi của bạn đã được gửi. Vui lòng chờ xét duyệt của người quản trị.');
							window.location.replace('<?php echo site_url(); ?>');
						</script>
					<?php
				endif;
				// $current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				// header('Location: '.site_url());
			else:
				// $current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				// header('Location: '.$current_url);	
				?>
					<script type="text/javascript">
						window.location.replace('<?php echo site_url(); ?>');
					</script>
				<?php
			endif;	

			// send email to admin website
			if( get_option('check_emailadmin') ):
				if( get_option('email_admin') ):
					$email = get_option('email_admin');
				else:
					$email = get_option('admin_email');
				endif;

				if( get_option('email_subject') ):
					$subject = get_option('email_subject');
				else:
					$subject = 'Bạn vừa nhận 1 câu hỏi trên website.';
				endif;

				if( get_option('email_content') ):
					$body = get_option('email_content');
				else:
					$body = 'Có 1 khách hàng đã đặt câu hỏi trên website của bạn. Vui lòng đăng nhập website để xem chi tiết.';
				endif;

				$headers = array('Content-Type: text/html; charset=UTF-8');
	    		wp_mail( $email, $subject, $body, $headers );
	    	endif;
		endif;							
	endif;	
}
add_action('init', 'excute_submit_form_datcauhoi');

// add kv_handle_attachment
function kv_handle_attachment($file_handler,$post_id,$set_thu=false) {
	// check to make sure its a successful upload
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$attach_id = media_handle_upload( $file_handler, $post_id );

 	// If you want to set a featured image frmo your uploads. 
	set_post_thumbnail($post_id, $attach_id);
	return $attach_id;
}

// used for tracking error messages
function write_here_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// add sidebar page hoi dap
add_action( 'widgets_init', 'add_sidebar_page_hoidap' );
function add_sidebar_page_hoidap() {
    register_sidebar( array(
        'name' => __( 'Sidebar Q&A page', 'custom' ),
        'id' => 'hoidap_sidebar',
        'description' => __( 'Widgets in this area will be shown on Sidebar Q&A page', 'custom' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<span class="widget-title "><span>',
        'after_title'   => '</span></span>',
    ) );
}

// add ajax_register
function ajax_register(){

	$content = '';	

    if( $_POST['new_email'] == '' || $_POST['new_password'] == '' ):
		$content .= 'Bạn phải nhập thông tin.';
	else:
		if( !is_email($_POST['new_email']) ):
			$content .= 'Vui lòng nhập một địa chỉ email hợp lệ.';
		else:
			$api_url     = 'https://www.google.com/recaptcha/api/siteverify';
			if( get_option('site_key') ):
				$site_key =  get_option('site_key');
			else:
				$site_key    = '6LeWOMsUAAAAAH58E0E-7X8Zq1Ft3sKQwVCHBwVT';
			endif;
			if( get_option('secret_key') ):
				$secret_key =  get_option('secret_key');
			else:
				$secret_key    = '6LeWOMsUAAAAAH58E0E-7X8Zq1Ft3sKQwVCHBwVT';
			endif;
		    //lấy dữ liệu được post lên
		    $response = $_POST["captcha"];
		    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response}");
		    $captcha_success = json_decode($verify);
		    // $content .= 'aaa'.$_POST["captcha"];
			if( $_POST["captcha"] == '' ):
			    $content .= 'Vui lòng kiểm tra mã bảo vệ.!!!';
			else:
				global $wpdb; 
			    $username  = $wpdb->escape($_POST['new_email']);  
			    $password = $wpdb->escape($_POST['new_password']);
			    $email  = $wpdb->escape($_POST['new_email']); 

			    $login_data = array();  
			    $login_data['user_login'] = $username ;  
			    $login_data['user_password'] = $password;
			    $login_data['user_email'] = $email; 

			    $user_verify = wp_signon( $login_data, false );

			    if ( is_wp_error($user_verify) ) :
			    	if( email_exists( $username ) ):
			    		$content .= 'Địa chỉ email này đã được sử dụng';
			    	else:
			    		$new_user_id = wp_create_user( $username, $password, $email );
			    		$user_id_role = new WP_User($new_user_id);
						$user_id_role->set_role('contributor');

			    		if( is_wp_error($new_user_id) ):
			    			$content .= 'Có lỗi, vui lòng thử lại sau';
			    		else:
			    			autoLoginUser($new_user_id);
			    		endif;
			    	endif;
			    endif;
			endif;
		endif;
	endif;	

	wp_send_json_success($content);
    die();
}
add_action('wp_ajax_nopriv_ajax_register', 'ajax_register'); 
add_action('wp_ajax_ajax_register', 'ajax_register');

// function autologin
function autoLoginUser($user_id){
	$user = get_user_by( 'id', $user_id );
	if( $user ) {
		wp_set_current_user( $user_id, $user->user_login );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user->user_login, $user);
	}
}

// add ajax_load_all_reply
function ajax_load_all_reply(){

	header("Content-Type: text/html");

	if( get_option('soluong_cauhoi') ):
		$ppp = get_option('soluong_cauhoi');
	else:
		$ppp = '1';
	endif;

	$args = array(
		'post_type' => 'cauhoi',
		'posts_per_page' => $ppp,
		'post_status' => 'publish',
	);

	$loop = new WP_Query($args);
	if( $loop->have_posts() ):
		while ($loop->have_posts()) : $loop->the_post();
			global $post;			
			$author_id = $post->post_author;
			$cat = get_the_terms( $post->ID, 'chude');
			$user_id = get_current_user_id();
			$post_id = $post->ID;
			?>
				<div class="social-feed-box">
				    <div class="social-avatar">
				        <span class="image">
				        	<img src="<?php echo esc_url(get_avatar_url( $author_id )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />
				        </span>
				        <div class="media-body">
				            <p>
				                <strong><?php echo get_the_author(); ?></strong>
				                <span><?php echo __('đã hỏi','custom'); ?>:</span>
				            </p>
				            <small>
				                <span><?php echo get_the_date('d/m/Y H:i'); ?></span>
				            </small>
				        </div>
				    </div>
				    <div class="social-body">
				        <div class="title"><?php echo get_the_content(); ?></div>
				        <?php if( has_post_thumbnail() ): ?>
					        <div class="img_question">
					        	<?php the_post_thumbnail(); ?>
					        </div>
					    <?php endif; ?>
				        <div class="social-select m-b-xs">
				            <span class="bg-white"><?php echo __('Chủ đề','custom'); ?>:</span>
				            <ul class="list-unstyled list-inline">
				                <li onclick="get_question_by_category(<?php echo $cat[0]->term_id; ?>)">
				                    <a href="javascript:;"><?php echo $cat[0]->name; ?></a>
				                </li>
				            </ul>
				        </div>
				    </div>
				    <div class="social-footer has-active-reply-box" id="box_reply_<?php echo $post_id; ?>">
				        <div class="social-like-box">
				            <span>
				                <a href="<?php the_permalink(); ?>"><?php echo __('Xem chi tiết','custom'); ?></a>
				            </span>
				        </div>
				        <?php 
				        	$args_tl = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',
								'posts_per_page' => 3,								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tl = new WP_Query($args_tl);
							if( $loop_tl->have_posts() ):
								while ($loop_tl->have_posts()) : $loop_tl->the_post();
									global $post;			
									$author_id_1 = $post->post_author;
									?>
										<div data-author="<?php echo $author_id_1; ?>" class="social-comment">
										    <a href="javascript:;">
										        <span class="image">
										            <img src="<?php echo esc_url(get_avatar_url( $author_id_1 )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id_1 ); ?>" />  
										        </span>
										    </a>
										    <div class="media-body">
										        <div class="media-name">
										            <a href="javascript:;"><?php echo get_the_author(); ?></a>
										        </div>
										        <div class="title"><?php echo get_the_title(); ?></div>										        
										    </div>
										    <div class="media-footer">
										        <ul class="list-unstyled media-meta">
										            <li><?php echo get_the_date('d/m/Y H:i'); ?></li>
										        </ul>
										    </div>
										</div>
									<?php
								endwhile;
							endif;
							wp_reset_query();
				        ?>
				        <?php 
				        	$args_tt = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',
								'posts_per_page' => -1,								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tt = new WP_Query($args_tt);
							$total_tt = $loop_tt->found_posts;
							echo '<input type="text" id="total_traloi_'.$post_id.'" value="'.$total_tt.'" hidden>';
							wp_reset_query();
				        ?>
				        <div id="add-reply-<?php echo $post_id; ?>"></div>
				        <div class="social-reply">
				            <div class="media-body">
				                <div class="form-group">
				                    <textarea placeholder="<?php echo __('Viết trả lời','custom'); ?>..." rows="1" id="content_reply_<?php echo $post_id; ?>" class="form-control resize-textarea"></textarea>
				                </div>
				                <button <?php if( is_user_logged_in() ) echo 'data-user="'.$user_id.'"'; ?> id="btn_reply_<?php echo $post_id; ?>" class="btn"><i aria-hidden="true" class="fa fa-paper-plane"></i><?php echo __('Gửi','custom'); ?></button>
				                <?php if( !is_user_logged_in() ): ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').click(function() {
												$.magnificPopup.open({
										    		items: {
										        		src: '#popup_login' 
										    		},
										    		type: 'inline'
										      	});
											});
										});
									</script>
								<?php else: ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').attr('onclick','postQuestion(<?php echo $post_id; ?>)');
										});
									</script>	
								<?php endif; ?>
				            </div>
				        </div>
				    </div>
				</div>
			<?php
		endwhile;		
	endif;
	wp_reset_query(); 

	// check to show Load more
	$args2 = array(
		'post_type' => 'cauhoi',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);
	$loop2 = new WP_Query($args2);
	$totalpost = $loop2->found_posts;
	if( $totalpost > $ppp )	:
		?>
			<div class="btn_loadmore">
				<a id="loadmorequestion" data-page="1" data-total="<?php echo $totalpost; ?>" href="javascript:loadMoreQuestion(<?php echo $ppp; ?>)"><?php echo __('Xem thêm','custom'); ?>...</a>
			</div>
		<?php
	endif;
	wp_reset_query();

    die();
}
add_action('wp_ajax_nopriv_ajax_load_all_reply', 'ajax_load_all_reply'); 
add_action('wp_ajax_ajax_load_all_reply', 'ajax_load_all_reply');

// add ajax_load_reply
function ajax_load_reply(){

	header("Content-Type: text/html");
	$cat_id = $_POST["category"];

	if( get_option('soluong_cauhoi') ):
		$ppp = get_option('soluong_cauhoi');
	else:
		$ppp = '1';
	endif;

	$cat = array(
		'taxonomy' => 'chude',
		'field' => 'term_id',
		'terms' => $cat_id,
	);

	$args = array(
		'post_type' => 'cauhoi',
		'posts_per_page' => $ppp,
		'post_status' => 'publish',
		'tax_query' => array( $cat ),
	);

	$loop = new WP_Query($args);
	if( $loop->have_posts() ):
		while ($loop->have_posts()) : $loop->the_post();
			global $post;			
			$author_id = $post->post_author;
			$cat = get_the_terms( $post->ID, 'chude');
			$user_id = get_current_user_id();
			$post_id = $post->ID;
			?>
				<div class="social-feed-box">
				    <div class="social-avatar">
				        <span class="image">
				        	<img src="<?php echo esc_url(get_avatar_url( $author_id )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />
				        </span>
				        <div class="media-body">
				            <p>
				                <strong><?php echo get_the_author(); ?></strong>
				                <span><?php echo __('đã hỏi','custom'); ?>:</span>
				            </p>
				            <small>
				                <span><?php echo get_the_date('d/m/Y H:i'); ?></span>
				            </small>
				        </div>
				    </div>
				    <div class="social-body">
				        <div class="title"><?php echo get_the_content(); ?></div>
				        <?php if( has_post_thumbnail() ): ?>
					        <div class="img_question">
					        	<?php the_post_thumbnail(); ?>
					        </div>
					    <?php endif; ?>
				        <div class="social-select m-b-xs">
				            <span class="bg-white"><?php echo __('Chủ đề','custom'); ?>:</span>
				            <ul class="list-unstyled list-inline">
				                <li onclick="get_question_by_category(<?php echo $cat[0]->term_id; ?>)">
				                    <a href="javascript:;"><?php echo $cat[0]->name; ?></a>
				                </li>
				            </ul>
				        </div>
				    </div>
				    <div class="social-footer has-active-reply-box" id="box_reply_<?php echo $post_id; ?>">
				        <div class="social-like-box">
				            <span>
				                <a href="<?php the_permalink(); ?>"><?php echo __('Xem chi tiết','custom'); ?></a>
				            </span>
				        </div>
				        <?php 
				        	$args_tl = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',	
								'posts_per_page' => 3,							
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tl = new WP_Query($args_tl);
							if( $loop_tl->have_posts() ):
								while ($loop_tl->have_posts()) : $loop_tl->the_post();
									global $post;			
									$author_id_1 = $post->post_author;
									?>
										<div data-author="<?php echo $author_id_1; ?>" class="social-comment">
										    <a href="javascript:;">
										        <span class="image">
										            <img src="<?php echo esc_url(get_avatar_url( $author_id_1 )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id_1 ); ?>" />  
										        </span>
										    </a>
										    <div class="media-body">
										        <div class="media-name">
										            <a href="javascript:;"><?php echo get_the_author(); ?></a>
										        </div>
										        <div class="title"><?php echo get_the_title(); ?></div>
										    </div>
										    <div class="media-footer">
										        <ul class="list-unstyled media-meta">
										            <li><?php echo get_the_date('d/m/Y H:i'); ?></li>
										        </ul>
										    </div>
										</div>
									<?php
								endwhile;
							endif;
							wp_reset_query();
				        ?>
				        <?php 
				        	$args_tt = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',
								'posts_per_page' => -1,								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tt = new WP_Query($args_tt);
							$total_tt = $loop_tt->found_posts;
							echo '<input type="text" id="total_traloi_'.$post_id.'" value="'.$total_tt.'" hidden>';
							wp_reset_query();
				        ?>
				        <div id="add-reply-<?php echo $post_id; ?>"></div>
				        <div class="social-reply">
				            <div class="media-body">
				                <div class="form-group">
				                    <textarea placeholder="<?php echo __('Viết trả lời','custom'); ?>..." rows="1" id="content_reply_<?php echo $post_id; ?>" class="form-control resize-textarea"></textarea>
				                </div>
				                <button <?php if( is_user_logged_in() ) echo 'data-user="'.$user_id.'"'; ?> id="btn_reply_<?php echo $post_id; ?>" class="btn"><i aria-hidden="true" class="fa fa-paper-plane"></i><?php echo __('Gửi','custom'); ?></button>
				                <?php if( !is_user_logged_in() ): ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').click(function() {
												$.magnificPopup.open({
										    		items: {
										        		src: '#popup_login' 
										    		},
										    		type: 'inline'
										      	});
											});
										});
									</script>
								<?php else: ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').attr('onclick','postQuestion(<?php echo $post_id; ?>)');
										});
									</script>	
								<?php endif; ?>
				            </div>
				        </div>
				    </div>
				</div>
			<?php
		endwhile;
	endif;
	wp_reset_query(); 

	// check to show Load more
	$cat2 = array(
		'taxonomy' => 'chude',
		'field' => 'term_id',
		'terms' => $cat_id,
	);
	$args2 = array(
		'post_type' => 'cauhoi',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'tax_query' => array( $cat2 ),
	);
	$loop2 = new WP_Query($args2);
	$totalpost = $loop2->found_posts;
	if( $totalpost > $ppp )	:
		?>
			<div class="btn_loadmore">
				<a id="loadmorequestion" data-page="1" data-total="<?php echo $totalpost; ?>" href="javascript:loadMoreQuestionCategory('<?php echo $ppp; ?>','<?php echo $cat_id; ?>')"><?php echo __('Xem thêm','custom'); ?>...</a>
			</div>
		<?php
	endif;
	wp_reset_query();

    die();
}
add_action('wp_ajax_nopriv_ajax_load_reply', 'ajax_load_reply'); 
add_action('wp_ajax_ajax_load_reply', 'ajax_load_reply');

// add ajax_post_reply
function ajax_post_reply(){

	header("Content-Type: text/html");
	$title = $_POST["content"];
	$question_id = $_POST["question_id"];
	$user_id = $_POST["user_id"];
	$time = $_POST["time"];

	$users = wp_get_current_user();
	$user_level =  $users->user_level;

	$kd_traloi = get_option('kiemduyet_traloi');

	if( $kd_traloi == '1' ):		
		if( $user_level <= '2' ):
			$status = 'pending';
		else:
			$status = 'publish';
		endif;
	else:
		$status = 'publish';
	endif;
	$my_post = array(
	  	'post_title'    => $title,
	  	'post_status'   => $status,		  	
		'post_type' => 'traloi',
		'post_author' => $user_id,
		'meta_input'    => array(
			'parentid_question' => $question_id,
		),											
	);

	$errors = write_here_errors()->get_error_messages();
	if( empty($errors) ):
		$post_id = wp_insert_post( $my_post );
		if( $kd_traloi == '1' ):		
			if( $user_level <= '2' ):
				if( get_option('notification_cautraloi') ): 
					?>
						<script type="text/javascript">
							alert('<?php echo get_option('notification_cautraloi'); ?>');
						</script>
					<?php
				else:
					?>
						<script type="text/javascript">
							alert('Câu trả lời đã được gửi. Vui lòng chờ xét duyệt của người quản trị.');
						</script>
					<?php
				endif;
			endif;
		endif;
		$args = array(
	        'post_type' => 'traloi',
			'post_status' => 'publish',	
	        'p' => $post_id,
	    );
	    $my_posts = new WP_Query($args);
	    if($my_posts->have_posts()) : 
	    	while ( $my_posts->have_posts() ) : $my_posts->the_post();
	    		global $post;			
				$author_id = $post->post_author;
				$user_traloi = get_userdata( $author_id );
				$usertl_roles = $user_traloi->roles;

				// send email for custom
				if( get_option('check_emailcustomer') ):
					if( $usertl_roles[0] == 'administrator' ):
						if( $time == '1' ):
							$author_cauhoi = get_post_field ('post_author', $question_id);
							$email_cauhoi = get_the_author_meta( 'user_email' , $author_cauhoi ); 
							$email = $email_cauhoi;

							if( get_option('customer_subject') ):
								$subject = get_option('customer_subject');
							else:
								$subject = 'Bạn vừa nhận được câu trả lời.';
							endif;

							if( get_option('customer_content') ):
								$body = get_option('customer_content');
							else:
								$body = 'Câu hỏi mà bạn đã hỏi đã có câu trả lời. Vui lòng truy cập website để xem chi tiết.';
							endif;

							$headers = array('Content-Type: text/html; charset=UTF-8');
				    		wp_mail( $email, $subject, $body, $headers );
				    	endif;
					endif;
				endif;
				?>
					<div data-author="<?php echo $author_id; ?>" class="social-comment">
					    <a href="javascript:;">
					        <span class="image">
					            <img src="<?php echo esc_url(get_avatar_url( $author_id )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />  
					        </span>
					    </a>
					    <div class="media-body">
					        <div class="media-name">
					            <a href="javascript:;"><?php echo get_the_author(); ?></a>
					        </div>
					        <div class="title"><?php echo get_the_title(); ?></div>
					    </div>
					    <div class="media-footer">
					        <ul class="list-unstyled media-meta">
					            <li><?php echo get_the_date('d/m/Y H:i'); ?></li>
					        </ul>
					    </div>
					</div>
				<?php
			endwhile;
		endif;
		wp_reset_query(); 
	endif;
    die();
}
add_action('wp_ajax_nopriv_ajax_post_reply', 'ajax_post_reply'); 
add_action('wp_ajax_ajax_post_reply', 'ajax_post_reply');

// add ajax_loadmore_question
function ajax_loadmore_question(){

	header("Content-Type: text/html");
	$offset = $_POST["offset"];
  	$ppp = $_POST["ppp"];

	$args = array(
		'post_type' => 'cauhoi',
		'posts_per_page' => $ppp,
    	'offset' => $offset,
	);

	$loop = new WP_Query($args);
	if( $loop->have_posts() ):
		while ($loop->have_posts()) : $loop->the_post();
			global $post;			
			$author_id = $post->post_author;
			$cat = get_the_terms( $post->ID, 'chude');
			$user_id = get_current_user_id();
			$post_id = $post->ID;
			?>
				<div class="social-feed-box">
				    <div class="social-avatar">
				        <span class="image">
				        	<img src="<?php echo esc_url(get_avatar_url( $author_id )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />
				        </span>
				        <div class="media-body">
				            <p>
				                <strong><?php echo get_the_author(); ?></strong>
				                <span><?php echo __('đã hỏi','custom'); ?>:</span>
				            </p>
				            <small>
				                <span><?php echo get_the_date('d/m/Y H:i'); ?></span>
				            </small>
				        </div>
				    </div>
				    <div class="social-body">
				        <div class="title"><?php echo get_the_content(); ?></div>
				        <?php if( has_post_thumbnail() ): ?>
					        <div class="img_question">
					        	<?php the_post_thumbnail(); ?>
					        </div>
					    <?php endif; ?>
				        <div class="social-select m-b-xs">
				            <span class="bg-white"><?php echo __('Chủ đề','custom'); ?>:</span>
				            <ul class="list-unstyled list-inline">
				                <li onclick="get_question_by_category(<?php echo $cat[0]->term_id; ?>)">
				                    <a href="javascript:;"><?php echo $cat[0]->name; ?></a>
				                </li>
				            </ul>
				        </div>
				    </div>
				    <div class="social-footer has-active-reply-box" id="box_reply_<?php echo $post_id; ?>">
				        <div class="social-like-box">
				            <span>
				                <a href="<?php the_permalink(); ?>"><?php echo __('Xem chi tiết','custom'); ?></a>
				            </span>
				        </div>
				        <?php 
				        	$args_tl = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tl = new WP_Query($args_tl);
							if( $loop_tl->have_posts() ):
								while ($loop_tl->have_posts()) : $loop_tl->the_post();
									global $post;			
									$author_id_1 = $post->post_author;
									?>
										<div data-author="<?php echo $author_id_1; ?>" class="social-comment">
										    <a href="javascript:;">
										        <span class="image">
										            <img src="<?php echo esc_url(get_avatar_url( $author_id_1 )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id_1 ); ?>" />  
										        </span>
										    </a>
										    <div class="media-body">
										        <div class="media-name">
										            <a href="javascript:;"><?php echo get_the_author(); ?></a>
										        </div>
										        <div class="title"><?php echo get_the_title(); ?></div>
										    </div>
										    <div class="media-footer">
										        <ul class="list-unstyled media-meta">
										            <li><?php echo get_the_date('d/m/Y H:i'); ?></li>
										        </ul>
										    </div>
										</div>
									<?php
								endwhile;
							endif;
							wp_reset_query();
				        ?>
				        <?php 
				        	$args_tt = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',
								'posts_per_page' => -1,								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tt = new WP_Query($args_tt);
							$total_tt = $loop_tt->found_posts;
							echo '<input type="text" id="total_traloi_'.$post_id.'" value="'.$total_tt.'" hidden>';
							wp_reset_query();
				        ?>
				        <div id="add-reply-<?php echo $post_id; ?>"></div>
				        <div class="social-reply">
				            <div class="media-body">
				                <div class="form-group">
				                    <textarea placeholder="<?php echo __('Viết trả lời','custom'); ?>..." rows="1" id="content_reply_<?php echo $post_id; ?>" class="form-control resize-textarea"></textarea>
				                </div>
				                <button <?php if( is_user_logged_in() ) echo 'data-user="'.$user_id.'"'; ?> id="btn_reply_<?php echo $post_id; ?>" class="btn"><i aria-hidden="true" class="fa fa-paper-plane"></i><?php echo __('Gửi','custom'); ?></button>
				                <?php if( !is_user_logged_in() ): ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').click(function() {
												$.magnificPopup.open({
										    		items: {
										        		src: '#popup_login' 
										    		},
										    		type: 'inline'
										      	});
											});
										});
									</script>
								<?php else: ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').attr('onclick','postQuestion(<?php echo $post_id; ?>)');
										});
									</script>	
								<?php endif; ?>
				            </div>
				        </div>
				    </div>
				</div>
			<?php
		endwhile;
	endif;
	wp_reset_query(); 
    die();
}
add_action('wp_ajax_nopriv_ajax_loadmore_question', 'ajax_loadmore_question'); 
add_action('wp_ajax_ajax_loadmore_question', 'ajax_loadmore_question');

// add ajax_loadmore_question_category
function ajax_loadmore_question_category(){

	header("Content-Type: text/html");
	$offset = $_POST["offset"];
  	$ppp = $_POST["ppp"];
  	$cat_id = $_POST["category"];

  	$cat = array(
		'taxonomy' => 'chude',
		'field' => 'term_id',
		'terms' => $cat_id,
	);

	$args = array(
		'post_type' => 'cauhoi',
		'posts_per_page' => $ppp,
		'post_status' => 'publish',
    	'offset' => $offset,
    	'tax_query' => array( $cat ),
	);

	$loop = new WP_Query($args);
	if( $loop->have_posts() ):
		while ($loop->have_posts()) : $loop->the_post();
			global $post;			
			$author_id = $post->post_author;
			$cat = get_the_terms( $post->ID, 'chude');
			$user_id = get_current_user_id();
			$post_id = $post->ID;
			?>
				<div class="social-feed-box">
				    <div class="social-avatar">
				        <span class="image">
				        	<img src="<?php echo esc_url(get_avatar_url( $author_id )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />
				        </span>
				        <div class="media-body">
				            <p>
				                <strong><?php echo get_the_author(); ?></strong>
				                <span><?php echo __('đã hỏi','custom'); ?>:</span>
				            </p>
				            <small>
				                <span><?php echo get_the_date('d/m/Y H:i'); ?></span>
				            </small>
				        </div>
				    </div>
				    <div class="social-body">
				        <div class="title"><?php echo get_the_content(); ?></div>
				        <?php if( has_post_thumbnail() ): ?>
					        <div class="img_question">
					        	<?php the_post_thumbnail(); ?>
					        </div>
					    <?php endif; ?>
				        <div class="social-select m-b-xs">
				            <span class="bg-white"><?php echo __('Chủ đề','custom'); ?>:</span>
				            <ul class="list-unstyled list-inline">
				                <li onclick="get_question_by_category(<?php echo $cat[0]->term_id; ?>)">
				                    <a href="javascript:;"><?php echo $cat[0]->name; ?></a>
				                </li>
				            </ul>
				        </div>
				    </div>
				    <div class="social-footer has-active-reply-box" id="box_reply_<?php echo $post_id; ?>">
				        <div class="social-like-box">
				            <span>
				                <a href="<?php the_permalink(); ?>"><?php echo __('Xem chi tiết','custom'); ?></a>
				            </span>
				        </div>
				        <?php 
				        	$args_tl = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tl = new WP_Query($args_tl);
							if( $loop_tl->have_posts() ):
								while ($loop_tl->have_posts()) : $loop_tl->the_post();
									global $post;			
									$author_id_1 = $post->post_author;
									?>
										<div data-author="<?php echo $author_id_1; ?>" class="social-comment">
										    <a href="javascript:;">
										        <span class="image">
										            <img src="<?php echo esc_url(get_avatar_url( $author_id_1 )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id_1 ); ?>" />  
										        </span>
										    </a>
										    <div class="media-body">
										        <div class="media-name">
										            <a href="javascript:;"><?php echo get_the_author(); ?></a>
										        </div>
										        <div class="title"><?php echo get_the_title(); ?></div>
										    </div>
										    <div class="media-footer">
										        <ul class="list-unstyled media-meta">
										            <li><?php echo get_the_date('d/m/Y H:i'); ?></li>
										        </ul>
										    </div>
										</div>
									<?php
								endwhile;
							endif;
							wp_reset_query();
				        ?>
				        <?php 
				        	$args_tt = array(
								'post_type' => 'traloi',
								'post_status' => 'publish',
								'posts_per_page' => -1,								
								'meta_query' => array( 
									array(
										'key' => 'parentid_question',
            							'value' => $post_id,
									)
								),
								'ignore_sticky_posts' => true,
							);
							$loop_tt = new WP_Query($args_tt);
							$total_tt = $loop_tt->found_posts;
							echo '<input type="text" id="total_traloi_'.$post_id.'" value="'.$total_tt.'" hidden>';
							wp_reset_query();
				        ?>
				        <div id="add-reply-<?php echo $post_id; ?>"></div>
				        <div class="social-reply">
				            <div class="media-body">
				                <div class="form-group">
				                    <textarea placeholder="<?php echo __('Viết trả lời','custom'); ?>..." rows="1" id="content_reply_<?php echo $post_id; ?>" class="form-control resize-textarea"></textarea>
				                </div>
				                <button <?php if( is_user_logged_in() ) echo 'data-user="'.$user_id.'"'; ?> id="btn_reply_<?php echo $post_id; ?>" class="btn"><i aria-hidden="true" class="fa fa-paper-plane"></i><?php echo __('Gửi','custom'); ?></button>
				                <?php if( !is_user_logged_in() ): ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').click(function() {
												$.magnificPopup.open({
										    		items: {
										        		src: '#popup_login' 
										    		},
										    		type: 'inline'
										      	});
											});
										});
									</script>
								<?php else: ?>
									<script type="text/javascript">
										jQuery(function($) {
											$('#btn_reply_<?php echo $post_id; ?>').attr('onclick','postQuestion(<?php echo $post_id; ?>)');
										});
									</script>	
								<?php endif; ?>
				            </div>
				        </div>
				    </div>
				</div>
			<?php
		endwhile;
	endif;
	wp_reset_query(); 
    die();
}
add_action('wp_ajax_nopriv_ajax_loadmore_question_category', 'ajax_loadmore_question_category'); 
add_action('wp_ajax_ajax_loadmore_question_category', 'ajax_loadmore_question_category');

// add template single cauhoi
add_filter('single_template', 'cauhoi_single_template');
function cauhoi_single_template( $single ) {
    global $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'cauhoi' ) {
        if( file_exists( plugin_dir_path( __FILE__ ) . 'single-cauhoi.php' ) )
            return plugin_dir_path( __FILE__ ) . 'single-cauhoi.php';
    }
    return $single;
}

// add template single traloi
add_filter('single_template', 'cauhoi_single_template_traloi');
function cauhoi_single_template_traloi( $single ) {
    global $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'traloi' ) {
        if( file_exists( plugin_dir_path( __FILE__ ) . 'single-traloi.php' ) )
            return plugin_dir_path( __FILE__ ) . 'single-traloi.php';
    }
    return $single;
}

// remove button Add New in Admin page for user has not role vina_admin
function remove_add_new(){
	global $current_user;
    wp_get_current_user();
    $user = wp_get_current_user();
    $roles = $user->roles;
	if( $current_user->user_login != 'vina_admin' || $roles[0] == 'contributor' || $roles[0] == 'author' )
	{
		?>
			<style type="text/css">
				#adminmenu > li
				{
					display: none;
				}
				#adminmenu > li.menu_logo, #adminmenu > li#menu-dashboard, #adminmenu > li#menu-posts-cauhoi, #adminmenu > li#menu-users, #adminmenu > li#collapse-menu
				{
					display: block;
				}
				#menu-posts-cauhoi .wp-submenu > li:nth-child(3)
				{
					display: none;
				}
				.post-type-cauhoi .page-title-action
				{
					display: none;
				}				
				.post-type-traloi .page-title-action
				{
					display: none;
				}

			</style>
		<?php
	}
}
add_action( 'admin_footer', 'remove_add_new' );

// add columns purchase_info and customer_info
add_filter('manage_traloi_posts_columns', 'ST4_columns_traloi_head');
add_action('manage_traloi_posts_custom_column', 'ST4_columns_traloi_content', 10, 2);

function ST4_columns_traloi_head($columns) {
    $columns['first_column']  = __('Trả lời cho câu hỏi','custom');
    return $columns;
}
 
function ST4_columns_traloi_content($column_name, $post_ID) {
	global $post;
    if ($column_name == 'first_column') { 
		$parentid_question = get_post_meta( $post->ID, 'parentid_question', true );	
		$args = array(
	        'post_type' => 'cauhoi',
			'post_status' => 'publish',	
	        'p' => $parentid_question,
	    );
	    $my_posts = new WP_Query($args);
	    if($my_posts->have_posts()) :
	    	while ( $my_posts->have_posts() ) : $my_posts->the_post();
	    		?>
	    			<a target="_blank" href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?>...</a>
	    		<?php
	    	endwhile;
	    endif;
	    wp_reset_query();    
    }
}