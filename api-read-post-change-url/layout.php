<div class="container">
	<div class="row">
		<div class="col-3">
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
				<div class="sidenav">
					<?php foreach( $catechild as $cat ): ?>					
						<?php 
							$args = array(
								'post_status' => 'publish',
								'post_type' => 'post',
								'cat' => $cat->term_id,
								'posts_per_page' => -1,
								'ignore_sticky_posts' => true,
								'orderby'             => 'date',
								'order'               => 'DESC',
							);
							$recentPosts = new WP_Query( $args );
						?>
						<?php if( $recentPosts->have_posts() ): ?>
							<button class="dropdown-btn"><?php echo $cat->name; ?><i class="fa fa-caret-down"></i></button>
							<div class="dropdown-container">
								<?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>
								 	<a href="javascript:;" data-id="<?php echo get_the_ID(); ?>" data-href="<?php echo get_the_permalink(); ?>" class="post_link_detail"><?php echo get_the_title(); ?></a>
								<?php endwhile; ?>
							</div>
						<?php else: ?>
							<a href="<?php echo get_term_link( $cat ); ?>"><?php echo $cat->name; ?></a>
						<?php endif; ?>
						<?php wp_reset_query(); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			</div>
		</div>
		<div class="col-9">
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