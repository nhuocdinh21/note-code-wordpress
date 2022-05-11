<?php
/**
 * The blog template file.
 *
 * @package flatsome
 */

get_header();

?>

<div id="content" class="blog-wrapper blog-archive">
    <div class="row row_hfeaturedproject row_hfeaturedproject_cat">
        <div class="col pb-0">
            <div class="col-inner">
                <?php echo do_shortcode('[title text="'.__('Nổi bật','custom').'" class="mb_15"]'); ?>
                <?php 
                    echo flatsome_apply_shortcode( 'blog_featured', array(
                        'style'            => 'normal',
                        'expands'          => 'true',
                        'columns'          => '6',
                        'columns__sm'      => '4',
                        'columns__md'      => '4',
                        'slider_nav_style' => 'simple',
                        'slider_bullets'   => 'true',
                        'auto_slide'       => '5000',
                        'posts'            => '-1',
                        'show_date'        => 'false',
                        'excerpt'          => 'false',
                        'comments'         => 'false',
                        'image_height'     => '100%',
                        'image_size'       => 'original',
                        'text_align'       => 'left',
                        'class'            => 'grid_featured',
                    ) );
                ?>
            </div>
        </div>
    </div>
	<?php 
        get_template_part( 'template-parts/projects/layout', 'right-sidebar' ); 
    ?>
</div>


<?php get_footer(); ?>