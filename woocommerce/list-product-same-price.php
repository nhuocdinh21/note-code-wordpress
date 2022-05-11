<?php
// add product_related
	function shortcode_product_related() {
		ob_start();
			global $post;
			global $product;
			$price = $product->get_price();
			// get categories
			$terms = wp_get_post_terms( $post->ID, 'product_cat' );
			foreach ( $terms as $term ) $cats_array[] = $term->term_id;
			$query_args = array( 
				'orderby' => 'date', 
				'post__not_in' => array( $post->ID ), 
				'posts_per_page' => 15, 
				'no_found_rows' => 1, 
				'post_status' => 'publish', 
				'post_type' => 'product', 
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => $cats_array
					)
				),
				'meta_query' => array(
		            array(
		                'key' => '_price',
		                'value' => $price - 1000000,
		                'compare' => '>='
		            ),
		            array(
		                'key' => '_price',
		                'value' => $price + 1000000,
		                'compare' => '<='
		            )
		        )
			);
			$r = new WP_Query($query_args);
			if( $r->have_posts() ) : ?>
				<div class="related products">
					<h3 class="title"><?php echo __('Sản phẩm tương tự cùng phân khúc giá','custom'); ?></h3>
					<?php 
						$type = get_theme_mod( 'related_products', 'slider' );
						$repater['type']         = $type;
						$repater['columns']      = get_theme_mod( 'related_products_pr_row', 4 );
						$repater['class']        = get_theme_mod( 'equalize_product_box' ) ? 'equalize-box' : '';
						$repater['slider_style'] = 'simple';
						$repater['row_spacing']  = 'small';

					?>
					<?php get_flatsome_repeater_start( $repater ); ?>
						<?php while ($r->have_posts()) : $r->the_post(); global $product; ?>
							<?php wc_get_template_part( 'content', 'product' ); ?>
						<?php endwhile; ?>
					<?php get_flatsome_repeater_end( $repater ); ?>
				</div>
			<?php endif;
			wp_reset_query();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	add_shortcode( 'product_related', 'shortcode_product_related' );