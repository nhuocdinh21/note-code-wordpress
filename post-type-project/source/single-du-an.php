<?php
/**
 * The blog template file.
 *
 * @package flatsome
 */

get_header();

?>

<div id="content" class="blog-wrapper blog-single">
	<?php get_template_part( 'template-parts/projects/layout', 'right-sidebar' ); ?>
</div>

<?php get_footer(); ?>