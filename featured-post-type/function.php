<?php
// add columns for post type post
add_filter('manage_post_posts_columns', 'custom_posttype_columns_post_head');
add_action('manage_post_posts_custom_column', 'custom_posttype_columns_post_content', 10, 2);

function custom_posttype_columns_post_head($columns) {
    $new = array();
    foreach($columns as $key => $title) {
        if ( $key == 'date' ):
            $new['featured_column']  = __('Nổi bật','custom_posttype');
        endif;   
        $new[$key] = $title;
    }
    return $new;
}
 
function custom_posttype_columns_post_content($column_name, $post_ID) {
    global $post;
    if ($column_name == 'featured_column') {       
        $check = get_post_meta( $post->ID, 'featured_post', true ) == '1' ? 'checked' : '';
        echo '<input data-rank="featured_post" data-post="'.$post->ID.'" id="featured_post" '.$check.' type="checkbox" class="rank_top star_featured">'; 
        ?>
            <style type="text/css">
                .star_featured {
                    visibility:hidden;
                    font-size:30px;
                    cursor:pointer;
                }

                .star_featured:before {
                    content: "\2606";
                    position: absolute;
                    visibility:visible;
                    margin-top: 10px;
                    margin-left: -4px;
                }

                .star_featured:checked:before {
                   content: "\2605" !important;
                   position: absolute;
                   font-size: 1em !important;
                   margin-top: 10px !important;
                   color: #ffb100 !important;
                }
            </style>
        <?php 
    } 
}

add_action( 'admin_footer', 'zing_action_javascript' );
function zing_action_javascript() { 
    ?>
        <script>
            jQuery(document).ready(function($) {
                $('input.rank_top').on('change', function(event) {
                    event.preventDefault();
                    var v = '';
                    var post_id = $(this).data('post');
                    var rank = $(this).data('rank');
                    if(this.checked) {
                        v = '1';
                    }
                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
                        data: ({
                            action: 'update_featured_post',
                            step: 'update_post_rank',
                            v: v,
                            post_id: post_id,
                            rank: rank,
                        }),
                        success: function(res) {
                            console.log(res);
                        },
                        complete: function(){

                        }
                    });
                    return false;
                });
            });
        </script>
    <?php
} 

add_action("wp_ajax_nopriv_update_featured_post", "function_update_featured_post");
add_action("wp_ajax_update_featured_post", "function_update_featured_post");
function function_update_featured_post(){
    $step = $_POST['step'];
    $data = array();
    $step = $_POST['step'];
    switch ($step) {
        case 'update_post_rank':
            echo $_POST['post_id']." - ".$_POST['v']." - ".$_POST['rank'];
            update_post_meta( $_POST['post_id'], $_POST['rank'], $_POST['v'] );
        break;
    }
    echo json_encode($data);
    die();
}