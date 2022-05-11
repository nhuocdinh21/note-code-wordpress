<?php 
	$filter_args = array
	(
		'post_type' => 'partner',
		'posts_per_page' => -1,
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$tutorial_posts = new WP_Query($filter_args);
	$posts_array = array();
	while ( $tutorial_posts->have_posts() ) : $tutorial_posts->the_post(); 
	   $posts_array[] = strtolower(get_the_title()[0]);
	endwhile;
	$alphabet_array = range('a', 'z');
?>

<div class="filter_partners filter_posttype">
	<ul id="a-z">
		<li data-letter="#"><?php echo __('View All','custom'); ?></li>
		<li data-letter="number">0-9</li>
		<?php foreach( $alphabet_array as $letter ): ?>
			<li data-letter="<?php echo $letter; ?>"><?php echo $letter; ?></li>
		<?php endforeach; ?>
	</ul>
	<?php $i = -1; ?>
	<div id="filter-results">
		<div class="filter_key"><span></span></div>
		<?php if( $tutorial_posts->have_posts() ): ?>
			<div class="row grid_filter_partner row-small large-columns-3 medium-columns-3 small-columns-1">
				<?php while ( $tutorial_posts->have_posts() ) : $tutorial_posts->the_post(); $i++; ?>
					<?php 
						$img_src = get_the_post_thumbnail_url();
						if( $img_src ):
							$img_src = $img_src;
						else:
							$img_src = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
						endif;
					?>
					<?php if(is_numeric($posts_array[$i])): ?>
						<div class="col partner-item" data-letter="number">
							<div class="col-inner">
								<a href="<?php the_permalink(); ?>">
									<div class="box">
										<div class="box-image">
											<div class="image-cover">
												<img src="<?php echo $img_src; ?>">
											</div>
										</div>
										<div class="box-text">
											<div class="box-text-inner">
												<div class="partner-title"><?php echo get_the_title(); ?></div>
												<div class="partner-country"><?php echo get_field('country_partner', get_the_ID()); ?></div>
											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
					<?php else: ?>
						<div class="col partner-item" data-letter="<?php echo $posts_array[$i]; ?>">
							<div class="col-inner">
								<a href="<?php the_permalink(); ?>">
									<div class="box">
										<div class="box-image">
											<div class="image-cover">
												<img src="<?php echo $img_src; ?>">
											</div>
										</div>
										<div class="box-text">
											<div class="box-text-inner">
												<div class="partner-title"><?php echo get_the_title(); ?></div>
												<div class="partner-country"><?php echo get_field('country_partner', get_the_ID()); ?></div>
											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
					<?php endif; ?>
				<?php endwhile; ?> 
			</div>
		<?php endif; ?>
	</div>
</div>							
<?php wp_reset_query(); ?>