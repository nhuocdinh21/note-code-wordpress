<?php
/*
	Template name: Page - Sign in
*/
if( is_user_logged_in() ):
	wp_redirect(get_bloginfo('url')) ;
endif;
	get_header(); 
?>

	<div id="content" class="blog-wrapper blog-archive page-wrapper">
		<div class="row row-small layout_login align-center">
			<div class="col col-12 pb-0">
				<?php 
					$message = '';
					$action = $_GET['login'];
					if ( $action == 'failed' ):
						$user = $_GET['user'];
						$message .= '<strong>Lỗi</strong>: Mật khẩu bạn đã nhập cho tên người dùng  <strong>'.$user.'</strong> không chính xác.';												
					elseif( $action == 'empty' ):						
						$user = $_GET['user'];
						if( $user ):
							$message .= '<strong>Lỗi</strong>: Mật khẩu đang trống.<br>';
						else:
							$message .= '<strong>Lỗi</strong>: Tên người dùng đang trống.<br><strong>Lỗi</strong>: Mật khẩu đang trống.<br>';							
						endif;
					endif;
				?>
				<div class="cs_frmlogin">
					<div class="title_login"><?php echo __('Đăng nhập','custom'); ?></div>
					<?php if( $message ): ?>
						<script type="text/javascript">
							jQuery(function($) {
								$('.cs_frmlogin').addClass('error_frm');
							});
						</script>
						<div id="login_error"><?php echo $message; ?></div>
						<?php 						
							$user = $_GET['user'];
							if( $user ):
						?>
							<script type="text/javascript">
								jQuery(function($) {
									$('#user_login').val('<?php echo $user; ?>');
									$('#user_pass').focus();
								});
							</script>
						<?php else: ?>
							<script type="text/javascript">
								jQuery(function($) {
									$('#user_login').focus();
								});
							</script>
						<?php endif; ?>
					<?php endif; ?>
					<?php 
						$args = array(
							'redirect'       => $_SERVER["HTTP_REFERER"],
					        'label_log_in'   => __( 'Log in' ),
					        'form_id'        => 'seminar-login',
					        'label_username' => __( 'Username' ),
					        'label_password' => __( 'Password' ),
					        'label_remember' => __( 'Remember Me' ),
					        'id_username'    => 'user_login',
					        'id_password'    => 'user_pass',
					        'id_submit'      => 'wp-submit',
					        'remember'       => true,
					        'value_username' => NULL,
					        'value_remember' => true,
						);
						wp_login_form($args);					
					?>
				</div>				
				<?php while ( have_posts() ) : the_post(); ?>
					<?php do_action( 'flatsome_before_page_content' ); ?>
					
						<?php the_content(); ?>

						<?php if ( comments_open() || '0' != get_comments_number() ){
							comments_template(); } ?>

					<?php do_action( 'flatsome_after_page_content' ); ?>
				<?php endwhile; // end of the loop. ?>
			</div>
		</div>
	</div>	

<?php get_footer(); ?>