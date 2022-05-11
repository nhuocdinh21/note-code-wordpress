<?php 
//add new shortcode
require_once get_template_directory() . '/inc/builder/helpers.php';

require get_theme_file_path() .'/inc/shortcode/blog_project.php';
require get_theme_file_path() .'/inc/builder/blog_project.php';

// add footer_info
function shortcode_footer_info() {
	ob_start();
		$id_logo =  get_option( 'media_selector_attachment_id' );
		$url_logo = wp_get_attachment_url($id_logo);
		?>
			<div class="footer_info hide-for-small">
				<div class="info_left">
					<div class="ft_logo">
						<a href="<?php echo site_url(); ?>"><img src="<?php echo $url_logo; ?>"></a>
					</div>
				</div>
				<div class="info_right">
					<div class="ft_menu">
						<?php if ( has_nav_menu( 'footer' ) ) : ?>
					        <?php
						        wp_nav_menu( array(
						          'theme_location' => 'footer',
						          'menu_class'     => 'links footer-nav',
						          'depth'          => 1,
						          'fallback_cb'    => false,
						          'container'=> false,
						        ) );
					        ?>
				      	<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="footer_info_mobile show-for-small">
				<div class="ft_menu">
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
				        <?php
					        wp_nav_menu( array(
					          'theme_location' => 'footer',
					          'menu_class'     => 'footer-nav',
					          'depth'          => 1,
					          'fallback_cb'    => false,
					          'container'=> false,
					        ) );
				        ?>
			      	<?php endif; ?>
				</div>
			</div>
		<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'footer_info', 'shortcode_footer_info' );

// add header_post
function shortcode_header_post() {
	ob_start();
		?>
			<li class="header_post">
				<a href="<?php echo get_field('chon_trang_dang_tin','option'); ?>"><?php echo get_field('tieu_de_nut_dang_tin_desktop','option'); ?></a>
			</li>
		<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'header_post', 'shortcode_header_post' );

// add header_account
function shortcode_header_account() {
	ob_start();
		?>
			<li class="header_account">
				<?php if( is_user_logged_in() ): ?>
					<?php $current_user = wp_get_current_user(); ?>
					<?php if( $current_user->user_firstname ): ?>
						<a href="javascript:;"><?php echo $current_user->user_firstname; ?> <i class="icon-angle-down"></i></a>
					<?php else: ?>
						<a href="javascript:;"><?php echo strstr($current_user->user_email, '@', true); ?> <i class="icon-angle-down"></i></a>
					<?php endif; ?>
					<ul>
						<li>
							<a href="<?php echo get_field('quan_ly_thong_tin_lien_ket','option'); ?>" title="<?php echo get_field('quan_ly_thong_tin_tieu_de','option'); ?>"><?php echo get_field('quan_ly_thong_tin_tieu_de','option'); ?></a>
						</li>
						<li>
							<a href="<?php echo get_field('quan_ly_tin_dang_lien_ket','option'); ?>" title="<?php echo get_field('quan_ly_tin_dang_tieu_de','option'); ?>"><?php echo get_field('quan_ly_tin_dang_tieu_de','option'); ?></a>
						</li>
						<li>
							<a href="<?php echo wp_logout_url( home_url() ); ?>" title="<?php echo __('Đăng xuất','custom'); ?>"><?php echo __('Đăng xuất','custom'); ?></a>
						</li>
					</ul>
				<?php else: ?>
					<a href="<?php echo get_field('dang_nhap','option'); ?>"><?php echo __('Đăng nhập','custom'); ?></a>
				<?php endif; ?>
			</li>
		<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'header_account', 'shortcode_header_account' );

// add menu_mobile
function shortcode_menu_mobile() {
	ob_start();
	    wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'menu mobile_menu',
			'fallback_cb'    => 'custom_primary_menu_fallback',
			'container'      => false,
	    ) );
	    ?>
	    	<span class="next_nav"></span>
	    <?php	    
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'menu_mobile', 'shortcode_menu_mobile' );

// add custom_primary_menu_fallback
function custom_primary_menu_fallback(){
	wp_nav_menu( array(
		'theme_location' => 'primary_mobile',
		'menu_class'     => 'menu nav mobile_menu',
		'container'      => false,
    ) );
}

// add banner_project_cat
function shortcode_banner_project_cat() {
	ob_start();
		$obj = get_queried_object();
		?>
			<?php if( have_rows('banner_sidebar', $obj->taxonomy . '_' . $obj->term_id) ): ?>
				<div class="banner_project_cat">
					<?php while( have_rows('banner_sidebar', $obj->taxonomy . '_' . $obj->term_id) ) : the_row(); ?>
						<div class="img">
							<div class="img-inner">
								<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php else: ?>
				<?php if( have_rows('banner_sidebar_cat','option') ): ?>
					<div class="banner_project_page">
						<?php while( have_rows('banner_sidebar_cat','option') ) : the_row(); ?>
							<div class="img">
								<div class="img-inner">
									<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'banner_project_cat', 'shortcode_banner_project_cat' );

// add banner_project_page
function shortcode_banner_project_page() {
	ob_start();
		?>
			<?php if( have_rows('banner_sidebar') ): ?>
				<div class="banner_project_page">
					<?php while( have_rows('banner_sidebar') ) : the_row(); ?>
						<div class="img">
							<div class="img-inner">
								<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php else: ?>
				<?php if( have_rows('banner_sidebar','option') ): ?>
					<div class="banner_project_page">
						<?php while( have_rows('banner_sidebar','option') ) : the_row(); ?>
							<div class="img">
								<div class="img-inner">
									<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'banner_project_page', 'shortcode_banner_project_page' );

// add banner_search_project
function shortcode_banner_search_project() {
	ob_start();
		?>
			<?php if( have_rows('banner_sidebar_search','option') ): ?>
				<div class="banner_search_project">
					<?php while( have_rows('banner_sidebar_search','option') ) : the_row(); ?>
						<div class="img">
							<div class="img-inner">
								<a href="<?php echo get_sub_field('lien_ket'); ?>"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'banner_search_project', 'shortcode_banner_search_project' );