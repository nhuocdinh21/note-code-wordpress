<?php
	do_action('flatsome_before_blog');
?>

<?php if( is_archive() ): ?>
	<?php 
		$obj = get_queried_object();

		$args = array(
	        'post_status'         => 'publish',
	        'post_type'           => 'du-an',
	        'tax_query'           => array( 
	        	array(
		            'taxonomy'    => 'danh-muc',
		            'field'       => 'term_id',
		            'terms'       => $obj->term_id,
		        ),
	        ),
	        'meta_query'          => array(
	        	array(
		            'key'         => 'top_1',
		            'value'       => '1',
		        ),
	        ),
	        'posts_per_page'      => '-1',
	        'ignore_sticky_posts' => true,
	        'orderby'             => 'date',
	        'order'               => 'DESC',
	    );

	    $recentPosts = new WP_Query( $args );
	?>
	<?php if( $recentPosts->have_posts() ): ?>
		<div class="row row_hfeaturedproject row_hfeaturedproject_cat">
			<div class="col pb-0">
				<div class="col-inner">
					<?php echo do_shortcode('[title text="'.__('Nổi bật','custom').'" class="mb_15"]'); ?>
					<?php 
						$ids = array();
						while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
							array_push($ids, get_the_ID());
						endwhile; 
						$ids = implode(',', $ids);

						echo flatsome_apply_shortcode( 'blog_featured', array(
							'style'            => 'normal',
							'expands'          => 'true',
							'columns'          => '6',
							'columns__sm'      => '4',
							'columns__md'      => '4',
							'slider_nav_style' => 'simple',
							'slider_bullets'   => 'true',
							'auto_slide'       => '5000',
							'show_date'        => 'false',
							'excerpt'          => 'false',
							'comments'         => 'false',
							'image_height'     => '100%',
							'image_size'       => 'original',
							'text_align'       => 'left',
							'ids'              => $ids,
							'class'            => 'grid_featured',
						) );
						wp_reset_query(); 
					?>
				</div>
			</div>			
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php if( is_single() ): ?>
	<div class="top_project_info">
		<?php get_template_part( 'template-parts/projects/content','singletop'); ?>
	</div>
<?php endif; ?>

<?php 
	global $wpdb;
	$city = $wpdb->get_results( "SELECT * FROM province" );
?>

<div class="row layout_project">

	<div class="large-8 col">
		<?php if( is_search() || is_archive() ): ?>
			<div class="frm_search">
				<div class="title_frm"><?php echo __('Tìm kiếm theo điều kiện','custom'); ?>:</div>
				<form method="get" id="frm_searchbox" action="<?php echo site_url(); ?>">
					<input type="hidden" autocomplete="off" name="s">
					<div class="group_item">
						<div class="inner">
							<div class="item">
								<span class="title"><?php echo __('Chọn vị trí','custom'); ?>:</span>
								<select name="city" id="city">
							  		<option value="0"><?php echo __('Tỉnh/thành','custom'); ?></option>
							  		<?php foreach ( $city as $item ) : ?>
							    		<option value="<?php echo $item->provinceid; ?>" <?php if( $item->provinceid == $_GET['city'] ) echo 'selected'; ?>><?php echo $item->name; ?></option>
							  		<?php endforeach; ?>
								</select>
								<select name="district" id="district">
							  		<option value="0"><?php echo __('Quận/huyện','custom'); ?></option>
								</select>
								<select name="ward" id="ward">
							  		<option value="0"><?php echo __('Phường/xã','custom'); ?></option>
								</select>
								<select name="street" id="street">
							  		<option value="0"><?php echo __('Đường/phố','custom'); ?></option>
								</select>
							</div>							
						</div>
					</div>
					<div class="group_item">
						<div class="inner">
							<div class="item">
								<span class="title"><?php echo __('Loại hình','custom'); ?>:</span>
								<div class="item_input">
									<input type="radio" name="type" value="thue" <?php if( 'thue' == $_GET['type'] ) echo 'checked'; ?>>
									<label for="type"><?php echo __('Cho thuê','custom'); ?></label>
								</div>
								<div class="item_input">
									<input type="radio" name="type" value="ban" <?php if( 'ban' == $_GET['type'] ) echo 'checked'; ?>>
									<label for="type"><?php echo __('Bán','custom'); ?></label>
								</div>
							</div>
							<div class="item">
								<span class="title"><?php echo __('Giá','custom'); ?>:</span>
								<div class="item_input">
									<input type="radio" name="price" value="desc" <?php if( 'desc' == $_GET['price'] ) echo 'checked'; ?>>
									<label for="price">Cao nhất</label>
								</div>
								<div class="item_input">
									<input type="radio" name="price" value="asc" <?php if( 'asc' == $_GET['price'] ) echo 'checked'; ?>>
									<label for="price">Thấp nhất</label>
								</div>
								<div class="item_input">
									<input type="radio" name="price" value="negotiate" <?php if( 'negotiate' == $_GET['price'] ) echo 'checked'; ?>>
									<label for="price">Thỏa thuận</label>
								</div>
							</div>
							<div class="item">
								<button type="submit"><span><?php echo __('Tìm kiếm','custom'); ?></span></button>
								<input name="post_type" value="du-an" type="hidden">
							</div>							
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>
		<?php
			if(is_single()){
				get_template_part( 'template-parts/projects/single');
				// comments_template();
			} elseif( is_search() ){
				get_template_part( 'template-parts/projects/archive', 'list' );
			} else {			
				get_template_part( 'template-parts/projects/archive', 'list' );
			}
		?>
	</div>
	<div class="project-sidebar large-4 col hide-for-medium">
		<?php flatsome_sticky_column_open( 'blog_sticky_sidebar' ); ?>
		<?php
			if( is_archive() ):
				dynamic_sidebar('sidebar-project-cat');
			elseif( is_search() ):
				dynamic_sidebar('sidebar-search-project');
			else:
				dynamic_sidebar('sidebar-project-page');
			endif;
			
		?>
		<?php flatsome_sticky_column_close( 'blog_sticky_sidebar' ); ?>
	</div>
</div>

<?php
	do_action('flatsome_after_blog');
?>
