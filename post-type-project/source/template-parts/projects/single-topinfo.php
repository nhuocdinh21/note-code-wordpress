<?php if ( have_posts() ) : ?>

<?php /* Start the Loop */ ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'template-parts/project/content', 'singletop' ); ?>

<?php endwhile; ?>

<?php endif; ?>