<?php 
	$post_id = get_the_ID();

	global $wpdb;
    $city = $wpdb->get_row( "SELECT * FROM province where provinceid=".get_field('city',$post_id) );
    $district = $wpdb->get_row( "SELECT * FROM district where districtid=".get_field('district',$post_id) );
    $ward = $wpdb->get_row( "SELECT * FROM ward where wardid=".get_field('ward',$post_id) );
    $street = $wpdb->get_row( "SELECT * FROM street where streetid=".get_field('street',$post_id) );

    $total_view = get_post_field( 'total_view', $post_id );
?>

<div class="project_content_info">
	<div class="project_meta">
		<div class="project_id"><?php echo __('Mã tin đăng','custom'); ?>: #<?php echo get_the_ID(); ?></div>
		<div class="project_view"><?php echo __('Lượt xem','custom'); ?>: <?php echo number_format($total_view, 0, ',', '.'); ?></div>
	</div>
	<h1 class="project_title"><?php echo get_the_title(); ?></h1>
	<div class="hide-for-medium">
		<p class="project_address"><span><?php echo __('Địa chỉ','custom'); ?>:</span> <?php echo get_post_meta( $post_id, 'address', true ); ?> <?php echo $street->type . ' ' . $street->name; ?>, <?php echo $ward->type . ' ' . $ward->name; ?>, <?php echo $district->name; ?>, <?php echo $city->name; ?></p>
		<p class="project_acreage"><span><?php echo __('Diện tích','custom'); ?>:</span> <?php echo get_post_meta( $post_id, 'acreage', true ); ?> m²</p>
	</div>
	<div class="show-for-medium">
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
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_acreage"><span><?php echo __('Diện tích','custom'); ?>:</span> <?php echo get_post_meta( $post_id, 'acreage', true ); ?> m²</p>									
					</div>
				</div>
			</div>
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_address"><span><?php echo __('Địa chỉ','custom'); ?>:</span> <?php echo get_post_meta( $post_id, 'address', true ); ?> <?php echo $street->type . ' ' . $street->name; ?>, <?php echo $ward->type . ' ' . $ward->name; ?>, <?php echo $district->name; ?>, <?php echo $city->name; ?></p>									
					</div>
				</div>
			</div>			
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_date"><?php echo __('Cập nhật','custom'); ?>: <span><?php echo get_the_modified_date('d/m/Y • H:i'); ?></span></p>									
					</div>
				</div>
			</div>
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_author"><?php echo __('Người đăng','custom'); ?>: <span><?php echo $post_author; ?></span></p>									
					</div>
				</div>
			</div>
			<div class="line"></div>
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_namecontact"><?php echo __('Tên liên hệ','custom'); ?>: <span><?php echo $user_name_ct; ?></span> <?php if( $agency == 1 ): ?><span class="agency"><?php echo __('Môi giới','custom'); ?></span><?php endif; ?></p>				
					</div>
				</div>
			</div>
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_phonecontact"><?php echo __('Điện thoại','custom'); ?>: <span class="phoneEvent" raw="<?php echo $phone_ct; ?>"><?php echo __('Nhấp vào xem') ?></span></p>
					</div>
				</div>
			</div>
			<div class="item item_noicon">
				<div class="inner">
					<div class="i_right">
						<p class="project_timeslot"><?php echo __('Khung giờ liên hệ tiện nhất','custom'); ?>: <span><?php echo $time_slot_txt; ?></span></p>
					</div>
				</div>
			</div>
			<div class="line"></div>			
		</div>
	</div>	
	<div class="content_info">
		<div class="content_title"><?php echo __('Thông tin mô tả','custom'); ?>:</div>
		<?php the_content(); ?>
	</div>

	<div class="copy_link_mobile show-for-medium">
		<div class="line"></div>
		<div class="copy_link_project">
			<p class="copy_link"><?php echo __('Sao chép link này để gửi','custom'); ?>: <span class="btn_copylink" raw="<?php echo get_the_permalink(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/copy.png"></span></p>
		</div>
	</div>
</div>
