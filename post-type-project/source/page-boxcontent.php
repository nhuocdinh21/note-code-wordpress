<?php
/*
Template name: Page - Box Content
*/
get_header(); ?>

<?php do_action( 'flatsome_before_page' ); ?>

<div id="content" class="content-area page-boxcontent" role="main">
	<div class="row row_boxcontent">
		<div class="large-12 col pb-0">
			<div class="col-inner">
				
				<?php if(get_theme_mod('default_title', 0)){ ?>
				<header class="entry-header">
					<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
				</header>
				<?php } ?>

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
