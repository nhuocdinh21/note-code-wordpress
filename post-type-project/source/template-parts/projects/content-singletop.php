<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="row project_topinfo pb_large">
			<div class="col large-8 pb-0">
				<div class="col-inner">	
					<?php $images = get_field('hinh_anh_du_an'); ?>				
					<?php if( $images ): ?>
						<div class="slide_image_project owl-carousel">
							<?php foreach( $images as $image ): ?>
								<div class="item">
									<div class="inner">
										<img src="<?php echo $image; ?>">
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col large-4 pb-0 hide-for-medium">
				<div class="col-inner">
					<div class="project_information">
						<?php 
		                    $post_id = get_the_ID();

		                    $project_type = get_post_meta( $post_id, 'project_type', true );
		                    if( $project_type == 'thue' ):
		                    	$project_type_txt = __('Cho thuê','custom');
		                    elseif( $project_type == 'ban' ):
		                    	$project_type_txt = __('Bán','custom');
		                    endif;

		                    $price = get_post_meta( $post_id, 'price', true );
		                    $price_negotiate = get_post_meta( $post_id, 'price_negotiate', true );

		                    $author_id = get_post_field( 'post_author', $post_id );
		                    $firstname = get_user_meta($author_id, 'first_name', true);

							if( $firstname ):
								$post_author = $firstname;
							else:
								$user_info = get_userdata($author_id);
								$post_author = strstr($user_info->user_email, '@', true);
							endif;

							$user_name_ct = get_post_meta( $post_id, 'user_name', true );
							$agency = get_post_meta( $post_id, 'agency', true );
							$phone_ct = get_post_meta( $post_id, 'user_phone', true );

							$time_slot = get_post_meta( $post_id, 'time_slot', true );
							if( $time_slot == 1 ):
								$time_slot_txt = __('Bất kỳ','custom');
							elseif( $time_slot == 2 ):
								$time_slot_txt = __('Sáng','custom');
							elseif( $time_slot == 3 ):
								$time_slot_txt = __('Trưa','custom');
							elseif( $time_slot == 4 ):
								$time_slot_txt = __('Chiều','custom');
							else:
								$time_slot_txt = __('Tối','custom');
							endif;
		                ?>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/tag.png"></div>
								<div class="i_right">
									<p class="project_price">
										<?php if( $price_negotiate ): ?>
											<span class="amount"><?php echo __('Thỏa thuận','custom'); ?></span>
										<?php else: ?>
											<span class="amount"> <?php echo number_format($price, 0, ',', '.'); ?> <span class="unit">vnđ</span></span>
										<?php endif; ?>
									</p>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dot.png"></div>
								<div class="i_right">
									<p class="project_status">
										<?php 
											$cats = get_the_terms($post_id, 'danh-muc');
											foreach( $cats as $cat ):
												if( $cat->parent != '0' ):
													$status = $cat->name;
												endif;											
											endforeach;
										?>
										<?php echo __('Đang','custom'); ?> <span><?php echo $project_type_txt; ?></span>
									</p>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/clock.png"></div>
								<div class="i_right">
									<p class="project_date"><?php echo __('Cập nhật','custom'); ?>: <span><?php echo get_the_modified_date('d/m/Y • H:i'); ?></span></p>									
								</div>
							</div>
						</div>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/account.png"></div>
								<div class="i_right">
									<p class="project_author"><?php echo __('Người đăng','custom'); ?>: <span><?php echo $post_author; ?></span></p>									
								</div>
							</div>
						</div>
						<div class="line"></div>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/account.png"></div>
								<div class="i_right">
									<p class="project_namecontact"><?php echo __('Tên liên hệ','custom'); ?>: <span><?php echo $user_name_ct; ?></span> <?php if( $agency == 1 ): ?><span class="agency"><?php echo __('Môi giới','custom'); ?></span><?php endif; ?></p>				
								</div>
							</div>
						</div>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/phone.png"></div>
								<div class="i_right">
									<p class="project_phonecontact"><?php echo __('Điện thoại','custom'); ?>: <span class="phoneEvent" raw="<?php echo $phone_ct; ?>"><?php echo __('Nhấp vào xem') ?></span></p>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="inner">
								<div class="i_left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/calendar.png"></div>
								<div class="i_right">
									<p class="project_timeslot"><?php echo __('Khung giờ liên hệ tiện nhất','custom'); ?>: <span><?php echo $time_slot_txt; ?></span></p>
								</div>
							</div>
						</div>
						<div class="line"></div>
						<div class="copy_link_project">
							<p class="copy_link"><?php echo __('Sao chép link này để gửi','custom'); ?>: <span class="btn_copylink" raw="<?php echo get_the_permalink(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/copy.png"></span></p>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endwhile; ?>

<?php endif; ?>