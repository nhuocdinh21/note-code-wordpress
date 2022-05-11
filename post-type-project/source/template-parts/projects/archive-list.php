<?php if ( have_posts() ) : ?>

<?php
	// add title 
	echo do_shortcode('[title text="'.__('Mới nhất','custom').'"]');

	// Create IDS
	$ids = array();
	while ( have_posts() ) : the_post();
		array_push($ids, get_the_ID());
	endwhile; // end of the loop.
	$ids = implode(',', $ids);
?>

	<?php
		echo flatsome_apply_shortcode( 'blog_projects', array(
			'style'         => 'vertical',
			'type'          => 'row',
			'columns'       => '1',
			'columns__sm'   => '1',
			'columns__md'   => '1',
			'show_date'     => 'text',
			'excerpt'       => 'false',
			'comments'      => 'false',
			'image_height'  => '89%',
			'image_width'   => '0',
			'text_pos'      => 'middle',
			'text_align'    => 'left',
			'ids'           => $ids,
			'class'         => 'list_projects',
		) );
	?>

<?php flatsome_posts_pagination(); ?>

<?php else : ?>

	<?php // get_template_part( 'template-parts/posts/content','none'); ?>

	<?php echo __('Không tìm thấy dữ liệu','custom'); ?>

<?php endif; ?>
