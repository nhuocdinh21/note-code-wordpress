<?php
// set view 
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// query arg order
$query = new WP_Query( 
    array( 
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ) 
);

// get list post type viewed
$query_2 = new WP_Query( 
    array( 
        'post_type'     => 'tour', //your post type
	    'posts_per_page' => 12, 
	    'meta_key'      => 'post_views_count', //the metakey previously defined
	    'orderby'       => 'meta_value_num',
	    'order'         => 'DESC'
    ) 
);