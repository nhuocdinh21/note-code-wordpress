<?php
/*
Template name: Page - Account
*/
if( !is_user_logged_in() ):
	wp_redirect( get_field('dang_nhap','option') ) ;
endif;

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$allowed_roles = array('subscriber', 'contributor', 'author', 'editor', 'administrator');

	if( !array_intersect($allowed_roles, $current_user->roles ) ):
		wp_redirect( get_field('dang_nhap','option') ) ;		
	endif;

	get_header(); 	
?>

<?php do_action( 'flatsome_before_page' ); ?>

<div id="content" class="content-area page-wrapper" role="main">
	<div class="row row-main">
		<div class="large-12 col pb-0">
			<div class="col-inner">
				
				<?php if(get_theme_mod('default_title', 0)){ ?>
				<header class="entry-header">
					<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
				</header>
				<?php } ?>

				<div class="frm_update_account">
					<form action="" method="post" id="frm_update_account">
						<div class="frm_inner">
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('Họ và tên','custom'); ?>:</div>
									<div class="i_right">
										<input type="text" name="user_name" value="<?php echo $current_user->user_firstname; ?>">
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('Email','custom'); ?>:</div>
									<div class="i_right"><?php echo $current_user->user_email; ?></div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('Số điện thoại','custom'); ?>:</div>
									<div class="i_right">
										<input type="tel" name="user_phone" value="<?php echo get_the_author_meta( 'user_phone', $current_user->ID ); ?>" pattern="[0-9]{10}">
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="text-center">
									<button type="submit"><?php echo __('Cập nhật','custom'); ?></button>
								</div>
							</div>
						</div>							
					</form>
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
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>

<?php 
	if( isset( $_POST['user_name'] ) && isset( $_POST['user_phone'] ) ):
		update_user_meta( $user_id, 'first_name', $_POST['user_name'] );
		update_user_meta( $user_id, 'user_phone', $_POST['user_phone'] );
		?>
			<script type='text/javascript'>
				alert('Cập nhật thông tin thành công');
	        	window.location = document.location.href;
	        </script>
		<?php		
	endif;
?>
