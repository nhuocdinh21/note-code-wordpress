<?php
// ajax count views when active cache
function my_count_views_script() {
    if( ! is_user_logged_in() ):
        if ( is_single() ) :        
            ?>
            <script type="text/javascript">
                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                jQuery(function($) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: 'my_count_views',
                            post_id: <?php the_ID(); ?>
                        },
                        success: function(data) {
                            if( $('.post_view').length > 0 ){
                                $('.post_view span').text(data); // show count view value
                            } 
                        }
                    });
                });            
            </script>
            <?php
        endif;
    endif;
}
add_action( 'wp_footer', 'my_count_views_script' ); 

function my_count_views() {
    if ( isset( $_POST['post_id'] ) && $_POST['post_id'] ) {
        $post_id = intval( $_POST['post_id'] );
        $views = intval( get_post_meta( $post_id, 'post_views_count', true ) ) ?: 0;
        $views += 1;

        update_post_meta( $post_id, 'post_views_count', $views );
        echo $views;
    }

    wp_die();
}
add_action( 'wp_ajax_my_count_views', 'my_count_views' );
add_action( 'wp_ajax_nopriv_my_count_views', 'my_count_views' );  
   