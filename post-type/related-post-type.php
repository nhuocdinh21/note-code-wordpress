<?php
global $post;
$categories = get_the_terms( $post->ID, 'tour_category');

if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
	// var_dump($categories[0]->term_id);
	$cat = array(
	   'taxonomy' => 'tour_category',
	   'field'    => 'term_id',
	   'terms'    => $categories[0]->term_id,
	);
	$args = array(
		'tax_query'         => array( $cat ),
		'post__not_in'      => array($post->ID),
		'posts_per_page'    => 12,
		'caller_get_posts'  => 1
	);
	$my_query = new WP_Query( $args );
	if( $my_query->have_posts() ) : ?>
		<div class="boxTour">
			<div class="tit"><span><?php echo __('Other tours','custom'); ?></span></div>
			<div class="cont">
				<?php 
					$ids = array();
					while( $my_query->have_posts() ) : $my_query->the_post();
						array_push($ids, get_the_ID());
					endwhile;
					$ids = implode(',', $ids);

					echo flatsome_apply_shortcode( 'blog_tours', array(
						'style'             => 'normal',
						'col_spacing'       => 'small',
						'columns__md'       => '2',
						'slider_nav_style'  => 'simple',
						'ids'               => $ids,
						'show_date'         => 'false',
						'excerpt'           => 'false',
						'comments'          => 'false',
						'image_height'      => '65%',
						'image_size'        => 'original',
						'image_hover'       => 'zoom',
						'text_align'        => 'left',
						'class'             => 'grid_tours slide_tours',
					) );
				?>
			</div>
		</div>
	<?php endif;
}
wp_reset_query(); 