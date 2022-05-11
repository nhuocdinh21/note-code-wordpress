<?php
	// do_action('flatsome_before_blog');
?>

<div class="row">
	<div class="large-12 col <?php if( is_single() ) echo 'pb-0'; ?>">
	<?php
		if(is_single()){
			get_template_part( 'template-parts/course/single');
			// comments_template();
		} elseif( is_search() ){
			get_template_part( 'template-parts/course/archive', 'course' );
		} else{
			$obj = get_queried_object();
			echo do_shortcode('[title text="'.$obj->name.'" style="center"]');

			$args = array(
		        'post_type'   => 'khoa-hoc',
		        'post_status' => 'publish',
		        'numberposts' => 6,
		        'ignore_sticky_posts' => true,
		        'tax_query' => array(			        	
					array(
			            'taxonomy' => 'danh-muc-khoa-hoc',
						'field'    => 'term_id',
						'terms'    => $obj->term_id,
			        ),
		        ),
		        'meta_query' => array(		        	
        			array(
			        	'key' => 'top_1',
        				'value' => '1'
			        ),
		        ),
			);

			$featuredCourse = new WP_Query( $args );
			if( $featuredCourse->have_posts() ):
				$ids = array();
				while ( $featuredCourse->have_posts() ) : $featuredCourse->the_post();
					array_push($ids, get_the_ID());
				endwhile;
				$ids = implode(',', $ids);

				echo do_shortcode('[title text="'.__('Featured course','custom').'" style="center" class="text_capitalize"]');

				echo flatsome_apply_shortcode( 'blog_course', array(
					'slider_nav_style'            => 'simple',
					'slider_nav_position'         => 'outside',
					'auto_slide'                  => '3000',
					'col_spacing'                 => 'small',
					'text_align'                  => 'left',
					'columns'                     => '3',
					'columns__md'                 => '2',
					'show_date'                   => 'false',
					'excerpt'                     => 'false',
					'comments'                    => 'false',
					'image_height'                => '65%',
					'image_size'                  => 'original',
					'ids'                         => $ids,
					'class'                       => 'grid_courses slide_featured_course',
				) );
				echo '<div class="line_featured_course"></div>';
			endif;
			wp_reset_query();


			// get list course
			echo do_shortcode('[title text="'.__('All','custom') .' '. $obj->name .'" style="center" class="text_capitalize"]');
			
			// get_template_part( 'template-parts/course/archive', 'course' );

			$post_per_page = 16;
			$post_show_more = 4;

			$the_query = new WP_Query( $args = array(
				'post_type'      => 'khoa-hoc',
				'posts_per_page' => $post_per_page,
				'tax_query'      => array(
					array(
						'taxonomy' => 'danh-muc-khoa-hoc',
						'field'    => 'term_id',
						'terms'    =>  $obj->term_id,
					)
				),
			) );

			$countp = $the_query ->found_posts;
			?>
				<?php if( $the_query->have_posts() ): ?>
					<div class="wrap_list_post wrap_list_post_<?php echo $obj->term_id; ?>">
						<div id="list_post_result_<?php echo $obj->term_id; ?>">
							<div class="row grid_courses large-columns-4 medium-columns-2 small-columns-2 row-small">
								<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
									<?php get_template_part( 'template-parts/course/course_html' ); ?>
								<?php endwhile; ?>
							</div>										
						</div>							
						<?php if( $countp > $post_per_page ): ?>
							<div class="loadmore_post text-center">
								<a href="javascript:;" class="button" id="load_more_<?php echo $obj->term_id; ?>" data-cat="<?php echo $obj->term_id; ?>" data-page="0" data-post-per-page="<?php echo $post_per_page; ?>" data-post-showmore="<?php echo $post_show_more; ?>" data-total="<?php echo $countp; ?>" data-taxonomy="<?php echo $obj->taxonomy; ?>" data-posttype="khoa-hoc"><i class="icons-spin5 animate-spin hidden"></i> <?php echo __('View more','custom'); ?></a>
							</div>
						<?php endif; wp_reset_query(); ?>
					</div>
				<?php else: ?>
					<div class="box_not_found_post">
						<p><?php echo __("It seems we can't find what you're looking for.","custom"); ?></p>
					</div>
				<?php endif; ?>
			<?php

			if( term_description( $obj ) ):
				echo '<div class="term_description"><div class="description_inner">'.term_description().'</div></div>';
			endif;
		}
	?>
	</div>

</div>

<?php do_action('flatsome_after_blog'); ?>