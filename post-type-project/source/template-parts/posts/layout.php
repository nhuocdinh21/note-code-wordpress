<?php
	do_action('flatsome_before_blog');
?>

<?php if(!is_single() && get_theme_mod('blog_featured', '') == 'top'){ get_template_part('template-parts/posts/featured-posts'); } ?>
<div class="row">
	<div class="large-12 col">
		<div class="col-inner">
			<?php if(!is_single() && get_theme_mod('blog_featured', '') == 'content'){ get_template_part('template-parts/posts/featured-posts'); } ?>

			<?php
				if(is_single()){
					get_template_part( 'template-parts/posts/single');
					// comments_template();
				} elseif( is_search() ){
					get_template_part( 'template-parts/posts/archive', get_theme_mod('blog_style_archive', '') );
				} else{
					get_template_part( 'template-parts/posts/archive', get_theme_mod('blog_style', 'normal') );
				}
			?>
		</div>			
	</div>

</div>

<?php do_action('flatsome_after_blog');
