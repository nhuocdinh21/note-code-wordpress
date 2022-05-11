<?php
//add new shortcode
require_once get_template_directory() . '/inc/builder/helpers.php';

require get_theme_file_path() .'/inc/builder/blog_course.php';
require get_theme_file_path() .'/inc/shortcode/blog_course.php';

require get_theme_file_path() .'/inc/builder/blog_search.php';
require get_theme_file_path() .'/inc/shortcode/blog_search.php';

// add ajax custom_loadmore_post -------------------------------------------------------------------------------------------------------
add_action('wp_ajax_nopriv_custom_loadmore_post', 'function_custom_loadmore_post');
add_action('wp_ajax_custom_loadmore_post', 'function_custom_loadmore_post');
function function_custom_loadmore_post(){ 

    ob_start();
    
    $cat           = $_POST['cat'];
    $offset        = $_POST['offset'];
    $post_per_page = $_POST['post_per_page'];
    $total         = $_POST['total'];
    $taxonomy      = $_POST['taxonomy'];
    $posttype      = $_POST['posttype'];

    $the_query = new WP_Query( $args = array(
        'post_type'      => $posttype,
        'posts_per_page' => $post_per_page,
        'offset'         => $offset,
        'tax_query'      => array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    =>  $cat,
            )
        ),
    ) );

    if ( $the_query->have_posts() ) : ?>
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <?php                 
                get_template_part( 'template-parts/course/course_html' );
            ?>
        <?php endwhile; ?>                                
    <?php endif;
    wp_reset_query();

    $html = ob_get_contents();
    ob_end_clean();

    $content = array(
        'html'   => $html,
    );
    wp_send_json($content);
    die();
}