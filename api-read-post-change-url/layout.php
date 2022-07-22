<?php 
	if( is_single() ):
		$current_cat_post = get_the_category();
		if( $current_cat_post[0]->parent == 0 ):
			$cat_post_id = $current_cat_post[0]->term_id;
		else:
			$cat_post_parent_id = get_ancestors( $current_cat_post[0]->term_id, 'category' ); 
			$cat_post_id = $cat_post_parent_id[0];
		endif;
	endif;
?>
<div class="container">
	<div class="row">
		<div class="col-12 col-sm-12 col-lg-3">
			<div class="col-inner">
				<?php 
					$catechild = get_terms( 'category', array(
					    'orderby'    => 'name',
					    'order'      =>'ASC',
					    'hide_empty' => 0,		    
					    'parent'     => 0,
					) );
				?>
				<?php if( $catechild ): ?>
					<div class="sidebar_new">
						<i class="fa fa-bars fa-2x toggle-btn" data-bs-toggle="collapse" data-bs-target="#nav_accordion"></i>						
						<ul class="nav flex-column collapse out" id="nav_accordion">
							<?php foreach( $catechild as $cat ): ?>
								<?php 
									$catesubchild = get_terms( 'category', array(
									    'orderby'    => 'name',
									    'order'      =>'ASC',
									    'hide_empty' => 0,		    
									    'parent'     => $cat->term_id,
									) );
								?>
								<?php if( $catesubchild ): ?>
									<li class="nav-item has-submenu">
										<a class="nav-link nav-link-parent" href="javascript:;"><?php echo $cat->name; ?><i class="fa fa-caret-down"></i></a>
										<ul class="submenu collapse <?php if( $cat_post_id && ( $cat_post_id == $cat->term_id || $current_cat_post[0]->term_id == $cat->term_id ) ): echo 'show'; endif; ?>">
											<?php foreach( $catesubchild as $catsub ): ?>
												<li class="nav-item has-submenu">
													<a class="nav-link nav-link-parent" href="javascript:;"><?php echo $catsub->name; ?><i class="fa fa-caret-down"></i></a>
													<?php 
														$args1 = array(
															'post_status' => 'publish',
															'post_type' => 'post',
															'cat' => $catsub->term_id,
															'posts_per_page' => -1,
															'ignore_sticky_posts' => true,
															'orderby'             => 'date',
															'order'               => 'DESC',
														);
														$recentPosts1 = new WP_Query( $args1 );
													?>
													<?php if( $recentPosts1->have_posts() ): ?>
														<ul class="submenu collapse <?php if( $cat_post_id && ( $cat_post_id == $catsub->term_id || $current_cat_post[0]->term_id == $catsub->term_id ) ): echo 'show'; endif; ?>">
															<?php while ( $recentPosts1->have_posts() ) : $recentPosts1->the_post(); ?>
															 	<a href="javascript:;" data-id="<?php echo get_the_ID(); ?>" data-href="<?php echo get_the_permalink(); ?>" class="nav-link post_link_detail"><?php echo get_the_title(); ?></a>
															<?php endwhile; ?>
														</ul>
													<?php endif; wp_reset_query(); ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</li>
								<?php else: ?>
									<?php if( $cat->count != 0 ): ?>
										<li class="nav-item has-submenu">
											<a class="nav-link nav-link-parent" href="javascript:;"><?php echo $cat->name; ?><i class="fa fa-caret-down"></i></a>
											<?php 
												$args2 = array(
													'post_status' => 'publish',
													'post_type' => 'post',
													'cat' => $cat->term_id,
													'posts_per_page' => -1,
													'ignore_sticky_posts' => true,
													'orderby'             => 'date',
													'order'               => 'DESC',
												);
												$recentPosts2 = new WP_Query( $args2 );
											?>
											<?php if( $recentPosts2->have_posts() ): ?>
												<ul class="submenu collapse <?php if( $current_cat_post && $current_cat_post[0]->term_id == $cat->term_id ): echo 'show'; endif; ?>">
													<?php while ( $recentPosts2->have_posts() ) : $recentPosts2->the_post(); ?>
													 	<a href="javascript:;" data-id="<?php echo get_the_ID(); ?>" data-href="<?php echo get_the_permalink(); ?>" class="nav-link post_link_detail"><?php echo get_the_title(); ?></a>
													<?php endwhile; ?>
												</ul>
											<?php endif; wp_reset_query(); ?>
										</li>
									<?php endif; ?>
								<?php endif; ?>									
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>					
			</div>
		</div>
		<div class="col-12 col-sm-12 col-lg-9">
			<div class="col-inner">
				<?php 
					if( is_single() ):
						$page_title = get_the_title();
					elseif( is_search() ):
						$page_title = __('Search','vinahost');
					else:
						$obj = get_queried_object();
						$page_title = $obj->name;
					endif;
				?>
				<nav aria-label="breadcrumb">
				  	<ol class="breadcrumb">
					    <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>"><?php echo bloginfo('name'); ?></a></li>
					    <li class="breadcrumb-item" aria-current="page"><span id="last_breadcrumb"><?php echo $page_title; ?></span></li>
				  	</ol>
				</nav>
				<?php if( is_single() ): ?>
					<div id="view_post_detail">
						<h1 id="post_title_detail"><?php the_title(); ?></h1>
						<div id="post_meta_detail"><?php echo get_the_date('j F Y'); ?></div>
						<div id="post_content_detail"><?php the_content(); ?></div>
					</div>
				<?php elseif( is_search() ): ?>

				<?php else: ?>
					<div id="view_post_detail">
						<h1 id="post_title_detail"></h1>
						<div id="post_meta_detail"></div>
						<div id="post_content_detail"></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>