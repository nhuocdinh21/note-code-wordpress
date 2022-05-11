<?php
/*
Template name: Page - Manage Projects
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

				<div class="list_post_by_user">
					<?php echo do_shortcode('[ajax_pagination posts_per_page="10"]'); ?>
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