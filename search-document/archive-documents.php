<div id="search_documents">
	<div class="row row-small pb_large">
		<div class="col large-3">
			<div class="col-inner">
				<div class="product_documents">
					<div class="box_title">
						<span><?php echo get_field('tieu_de_tim_san_pham_theo_san_pham','option'); ?></span>
					</div>
					<div class="search-filter">
						<?php 
							$product_documents = get_terms( 'tai-lieu-san-pham', array(
							    'orderby'    => 'menu_order',
							    'order'      =>'ASC',
							    'hide_empty' => 0,		    
							    'parent'     => 0,
							) );
						?>
						<?php if( $product_documents ): ?>
							<ul class="list-group">
								<?php foreach( $product_documents as $pro_cat ): ?>
									<li class="term_parent align-middle">
										<div class="nameContainer flex-grow">
											<a href="javascript:;" class="">
												<?php echo $pro_cat->name; ?>
												<label><input type="radio" name="product_cat" value="<?php echo $pro_cat->name ?>"></label>
											</a>
										</div>
										<div class="arrow">
											<i class="icon-angle-down"></i>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col large-9 pb-0">
			<div class="col-inner">
				<div class="search_input">
					<input type="text" class="search" placeholder="<?php echo get_field('mo_ta_o_nhap_tim_kiem_tai_lieu','option'); ?>">
					<div class="btn_search">
						<i class="icon-search"></i>
					</div>
				</div>
				<div class="table_documents">
					<ul class="table_title">
						<li><?php echo get_field('tieu_de_loai_tai_lieu_bang_tai_lieu','option'); ?></li>
						<li><?php echo get_field('tieu_de_ngon_ngu_bang_tai_lieu','option'); ?></li>
						<li><?php echo get_field('tieu_de_ten_tai_lieu_bang_tai_lieu','option'); ?></li>
					</ul>
					<?php 
						$args = array(
				            'post_type' => array(
				                'chi-tiet-tai-lieu',
				            ),
							'orderby'             => 'date',
							'order'               => 'DESC',
							'posts_per_page'      => -1,
							'ignore_sticky_posts' => true,
						);
						$recentPosts = new WP_Query( $args );
					?>
					<?php if( $recentPosts->have_posts() ): ?>
						<div class="table_content list">
							<?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>
								<div class="list--list-item package well">
									<div class="name hidden"><?php echo get_the_title(); ?></div>
									<div class="type hidden"><?php echo get_the_terms( get_the_ID(), 'tai-lieu' )[0]->name; ?></div>
									<div class="product_cat hidden"><?php echo get_the_terms( get_the_ID(), 'tai-lieu-san-pham' )[0]->name; ?></div>
									<ul>
										<li><?php echo get_the_terms( get_the_ID(), 'tai-lieu' )[0]->name; ?></li>
										<li><?php echo get_field('ngon_ngu_tai_lieu', get_the_ID()); ?></li>
										<li><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
									</ul>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; wp_reset_query(); ?>
					<ul class="pagination"></ul>
				</div>
			</div>
		</div>
	</div>
</div>