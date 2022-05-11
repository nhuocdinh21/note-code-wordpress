<?php
function custom_tab_projects( $atts, $content = null ){
	extract( shortcode_atts( array(    
		'class'       => '',
    	'visibility'  => '',
		'ids' => '',
		'number' => 12,
	), $atts ) );

	if ( isset( $atts[ 'ids' ] ) ) :
		$ids = explode( ',', $atts[ 'ids' ] );
		$ids = array_map( 'trim', $ids );
		$parent = '';
		$orderby = 'include';
    else:
      	$ids = array();
    endif;
    $args = array(
        'orderby'    => 'include',
        'include'    => $ids,
        'hide_empty' => '0',
    );
	ob_start();

	$term_cats = get_terms( 'du-an', $args );
	?>
		<div class="tabs <?php echo $class; ?> <?php echo $visibility; ?>">
			<div class="tab-links">
				<?php $i = 1; foreach ( $term_cats as $cat ): ?>
					<div class="tab <?php if( $i == 1 ) echo 'active'; ?>">
						<a href="#tab_<?php echo $cat->term_id; ?>">
							<?php echo $cat->name; ?>
						</a>
					</div>
				<?php $i++; endforeach; ?>   
			</div>
			<div class="tab-content">
			 <?php $i = 1; foreach ( $term_cats as $cat ): ?>
					<div id="tab_<?php echo $cat->term_id; ?>" class="tab-ct <?php if( $i == 1 ) echo 'active'; ?>">
						<?php 
							echo flatsome_apply_shortcode( 'blog_projects', array(
								'style'                                   => 'normal',
								'col_spacing'                             => 'small',
								'slider_nav_style'                        => 'circle',
								'slider_nav_position'                     => 'outside',
								'text_align'                              => 'left',
								'columns'                                 => '4',
								'columns__md'                             => '3',
								'show_date'                               => 'false',
								'excerpt_length'                          => '30',
								'comments'                                => 'false',
								'image_height'                            => '67%',
								'image_size'                              => 'original',
								'text_pos'                                => 'middle',
								'cat'                                     => $cat->term_id,
								'posts'                                   => $number,
								'class'                                   => 'grid_projects grid_hoverimg',
							) );
						?>
					</div>
				<?php $i++; endforeach; ?>
			</div>
		</div>
	<?php

	$content = ob_get_contents();
	ob_end_clean();
	return $content;

}
add_shortcode('tab_projects', 'custom_tab_projects');