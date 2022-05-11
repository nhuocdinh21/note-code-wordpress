<?php if ( have_posts() ) : ?>

<?php
	// Create IDS
	$ids = array();
	while ( have_posts() ) : the_post();
		array_push($ids, get_the_ID());
	endwhile; // end of the loop.
	$ids = implode(',', $ids);
?>

	<?php
		echo flatsome_apply_shortcode( 'blog_posts', array(
			'type'        => 'row',
			'text_align'  => 'left',
			'columns'     => '3',
			'columns__md' => '3',
			'show_date'   => 'false',
			'excerpt'     => 'false',
			'comments'    => 'false',
			'image_height'=> '60%',
			'ids'         => $ids,
			'class'       => 'grid_new_archives',
		) );
	?>

<?php flatsome_posts_pagination(); ?>

<?php else : ?>

	<?php get_template_part( 'template-parts/posts/content','none'); ?>

<?php endif; ?>
