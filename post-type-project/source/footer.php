<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */

global $flatsome_opt;
?>

</main><!-- #main -->

<?php
	if( is_archive() ):
		if( is_search() ):
			$banner_bottom_src = get_field('banner_bottom','option');
			$banner_bottom_link = get_field('lien_ket_banner_bottom','option');

			$banner_bottom_mobile_src = get_field('banner_bottom_mobile','option');
			$banner_bottom_mobile_link = get_field('lien_ket_banner_bottom_mobile','option');
		else:
			$obj = get_queried_object();
			$banner_bottom_src = get_field('banner_bottom', $obj->taxonomy . '_' . $obj->term_id);
			$banner_bottom_link = get_field('lien_ket_banner_bottom', $obj->taxonomy . '_' . $obj->term_id);

			$banner_bottom_mobile_src = get_field('banner_bottom_mobile', $obj->taxonomy . '_' . $obj->term_id);
			$banner_bottom_mobile_link = get_field('lien_ket_banner_bottom_mobile', $obj->taxonomy . '_' . $obj->term_id);	
		endif;	
	else:
		if( is_singular('du-an') ):
			$banner_bottom_src = get_field('banner_bottom');
			$banner_bottom_link = get_field('lien_ket_banner_bottom');

			$banner_bottom_mobile_src = get_field('banner_bottom_mobile');
			$banner_bottom_mobile_link = get_field('lien_ket_banner_bottom_mobile');

			// banner_bottom_src
			if( $banner_bottom_src ):
				$banner_bottom_src = $banner_bottom_src;
			else:
				$banner_bottom_src = get_field('banner_bottom','option');
			endif;

			// banner_bottom_link
			if( $banner_bottom_link ):
				$banner_bottom_link = $banner_bottom_link;
			else:
				$banner_bottom_link = get_field('lien_ket_banner_bottom','option');
			endif;

			// banner_bottom_mobile_src
			if( $banner_bottom_mobile_src ):
				$banner_bottom_mobile_src = $banner_bottom_mobile_src;
			else:
				$banner_bottom_mobile_src = get_field('banner_bottom_mobile','option');
			endif;

			// banner_bottom_mobile_link
			if( $banner_bottom_mobile_link ):
				$banner_bottom_mobile_link = $banner_bottom_mobile_link;
			else:
				$banner_bottom_mobile_link = get_field('lien_ket_banner_bottom_mobile','option');
			endif;
		else:
			if( is_search() ):
				$banner_bottom_src = get_field('banner_bottom','option');
				$banner_bottom_link = get_field('lien_ket_banner_bottom','option');

				$banner_bottom_mobile_src = get_field('banner_bottom_mobile','option');
				$banner_bottom_mobile_link = get_field('lien_ket_banner_bottom_mobile','option');
			else:
				$banner_bottom_src = get_field('banner_bottom');
				$banner_bottom_link = get_field('lien_ket_banner_bottom');

				$banner_bottom_mobile_src = get_field('banner_bottom_mobile');
				$banner_bottom_mobile_link = get_field('lien_ket_banner_bottom_mobile');
			endif;
		endif;
	endif;
?>

<?php if( $banner_bottom_src ): ?>
	<div class="banner_bottom hide-for-small">
		<a href="<?php echo esc_url( $banner_bottom_link ); ?>" target="_blank">
			<img src="<?php echo $banner_bottom_src; ?>">
		</a>
	</div>
<?php endif; ?>

<?php if( $banner_bottom_mobile_src ): ?>
	<div class="banner_bottom show-for-small">
		<a href="<?php echo esc_url( $banner_bottom_mobile_link ); ?>" target="_blank">
			<img src="<?php echo $banner_bottom_mobile_src; ?>">
		</a>
	</div>
<?php endif; ?>

<!-- add footer navigation -->
	<div class="mobile_navigation show-for-small">
		<div class="inner">
			<div class="item">
				<a href="<?php echo site_url(); ?>"><?php echo __('Trang chủ','custom'); ?></a>
			</div>
			<div class="item item_background">
				<a href="<?php echo get_field('chon_trang_dang_tin','option'); ?>"><?php echo get_field('tieu_de_nut_dang_tin_mobile','option'); ?></a>
			</div>
			<div class="item">
				<a href="<?php echo get_field('quan_ly_thong_tin_lien_ket','option'); ?>"><?php echo __('Cá nhân','custom'); ?></a>
			</div>
		</div>
	</div>
<!-- end footer navigation -->

<!-- add social contact right -->
	<div class="social_right">
		<a class="social_messenger" href="https://www.messenger.com/t/<?php echo get_field('fanpage_id','option'); ?>" target="_blank">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/messenger.png">
		</a>		
	</div>
<!-- end social contact right -->

<footer id="footer" class="footer-wrapper">

	<?php do_action('flatsome_footer'); ?>

</footer><!-- .footer-wrapper -->

</div><!-- #wrapper -->

<?php wp_footer(); ?>

<!-- add custom javascript -->
	<script type="text/javascript">
		jQuery(function($) {
			// add back to top		
			$('body').append('<div id="top" ><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top.png" alt="top"/></div>');
			$(window).scroll(function() {
				if($(window).scrollTop() > 100) {
					$('#top').fadeIn();
				} else {
					$('#top').fadeOut();
				}
			});
			$('#top').click(function() {
				$('html, body').animate({scrollTop:0},500);
			});

			// change text form comment
			$('#comments #reply-title').text('<?php echo __('Bình luận','custom'); ?>');

			$('#comments form input[type="submit"]').val('<?php echo __('Bình luận','custom'); ?>');

			// change form login
			if( $('.cs_frmlogin').length > 0 ){
		        $('.cs_frmlogin #user_login').attr('placeholder', '<?php echo __('Nhập email vào đây','custom'); ?>');
		        $('.cs_frmlogin #user_pass').attr('placeholder', '<?php echo __('Nhập mật khẩu vào đây','custom'); ?>');
		        $('.cs_frmlogin .login-lost-password').insertAfter( $('.cs_frmlogin .login-submit') );
		        $('.cs_frmlogin .login-username label').text('Email');
		        $('.cs_frmlogin .login-password label').text('Mật khẩu');
		    } 

		    // remove divider breadcumbs		    
		    $('.single-du-an .cs_breadcumbs .breadcumb .breadcrumb_last').prev('.divider').css('display', 'none');

		    // change arrow slide
		    $('.slide_image_project .owl-nav .owl-prev').html('<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow_left.png" >');
		    $('.slide_image_project .owl-nav .owl-next').html('<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow_right.png" >');
		});
	</script>
<!-- end custom javascript -->

</body>

<?php if( get_field('banner_left','option') ): ?>
	<div class="banner_left">
		<a href="<?php echo get_field('link_banner_left','option'); ?>" target="_blank"><img src="<?php echo get_field('banner_left','option'); ?>"></a>
	</div>
<?php endif; ?>

<?php if( get_field('banner_right','option') ): ?>
	<div class="banner_right">
		<a href="<?php echo get_field('link_banner_right','option'); ?>" target="_blank"><img src="<?php echo get_field('banner_right','option'); ?>"></a>
	</div>
<?php endif; ?>

</html>
