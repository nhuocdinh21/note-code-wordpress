<?php 
if( is_user_logged_in() ):
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;

	$current_user_last_name = get_user_meta( $current_user_id, 'last_name', 'true' );
	$current_user_first_name = get_user_meta( $current_user_id, 'first_name', 'true' );

	if( $current_user_first_name ):
		$current_user_name = $current_user_first_name;
	else:
		$current_user_name = $current_user_last_name;
	endif;

	if( $current_user_name ):
		$current_user_name = $current_user_name;
	else:
		$current_user_name = $current_user->user_login;
	endif;

	if( get_field('list_follow_artists') ):
		if (array_search($current_user_id , array_column(get_field('list_follow_artists'), 'id')) !== FALSE):
			$follow_status = 1;
		else:
			$follow_status = 0;
		endif;
	else:
		$follow_status = 0;
	endif;

endif;

if( get_field('list_follow_artists') ):
	$follow_count = count(get_field('list_follow_artists'));
else:
	$follow_count = 0;
endif;
?>
<div class="row layout_single_artist">
	<div class="col large-8 medium-7 artist_info">
		<div class="col-inner">
			<h1 class="artist_title">
				<?php echo get_the_title(); ?>
				<?php if( !is_user_logged_in() ): ?>
					<div class="follow_button follow_button_notlogin" data-open="#login-form-popup">
						<div class="inner">
							<span class="icon_follow"><i class="fas fa-plus"></i></span>
							<div class="not_following_text"><?php echo __('Follow','custom'); ?></div>
						</div>							
					</div>	
				<?php else: ?>					
					<div class="follow_button follow_button_login" data-id="<?php echo $current_user_id; ?>" data-follow="<?php echo $follow_status; ?>" data-postid="<?php echo get_the_ID(); ?>" data-username="<?php echo $current_user_name; ?>">
						<div class="inner">
							<span class="icon_follow"><i class="fas fa-plus"></i></span>
							<div class="following_text"><?php echo __('You are following','custom'); ?></div>
							<div class="not_following_text"><?php echo __('Follow','custom'); ?></div>
						</div>
					</div>	
				<?php endif; ?>				
			</h1>
			<div class="artist_description"><?php the_content(); ?></div>
		</div>
	</div>
	<div class="col large-4 medium-5 artist_imgtool">
		<div class="col-inner">
			<div class="artist_img">
				<?php 
					$img_src = get_the_post_thumbnail_url();
					if( $img_src ):
						$img_src = $img_src;
					else:
						$img_src = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
					endif;
				?>
				<div class="box">
					<div class="box-image">
						<div class="image-cover">
							<img src="<?php echo $img_src; ?>">
						</div>
						<?php if( !is_user_logged_in() ): ?>
							<div class="follow_button follow_button_notlogin" data-open="#login-form-popup">
								<div class="inner">
									<span class="icon_follow"><i class="fas fa-plus"></i></span>
									<div class="not_following_text"><?php echo __('Follow','custom'); ?></div>
								</div>							
							</div>	
						<?php else: ?>					
							<div class="follow_button follow_button_login" data-id="<?php echo $current_user_id; ?>" data-follow="<?php echo $follow_status; ?>" data-postid="<?php echo get_the_ID(); ?>" data-username="<?php echo $current_user_name; ?>">
								<div class="inner">
									<span class="icon_follow"><i class="fas fa-plus"></i></span>
									<div class="following_text"><?php echo __('Following','custom'); ?></div>
									<div class="not_following_text"><?php echo __('Follow','custom'); ?></div>
								</div>
							</div>	
						<?php endif; ?>	
					</div>
				</div>			
			</div>
			<div class="artist_name">
				<div class="title"><?php echo get_the_title(); ?></div>
				<div class="address"><?php echo get_field('city_artist'); ?>, <?php echo get_field('country_artist'); ?></div>
			</div>
			<div class="artist_follow">
				<a href="<?php echo esc_url(get_field('link_show_list_following','option')); ?>?ID=<?php echo get_the_ID(); ?>"><span><?php echo $follow_count; ?></span> <?php echo __('Followers','custom'); ?></a>				
			</div>
		</div>
	</div>	
</div>