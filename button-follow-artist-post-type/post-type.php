<?php 
// add post type artist
add_action( 'init', 'post_type_artist_register' );   

function post_type_artist_register() {   

    $labels = array( 
        'name' => __('Artist', 'custom'), 
        'singular_name' => __('Artist', 'custom'),
        'add_new' => __('Add new', 'custom'), 
        'add_new_item' => __('Add new','custom'), 
        'edit_item' => __('Edit Artist','custom'), 
        'new_item' => __('Add new','custom'), 

        'view_item' => __('View Artist','custom'), 
        'search_items' => __('Search Artist','custom'), 
        'not_found' => __('Search','custom'), 
        'not_found_in_trash' => __('There is nothing in the Trash','custom'), 
        'parent_item_colon' => ''
    );   

    $args = array( 
        'labels' => $labels, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true, 
        'menu_icon' => 'dashicons-money', 
        'rewrite' => array( 'slug' => 'artist', 'with_front'=> false ), 
        'capability_type' => 'post',
        'hierarchical' => true,
        'has_archive' => false,  
        'menu_position' => 31, 
        'supports' => array('title','editor','revisions','thumbnail','author')
    );
    register_post_type( 'artist' , $args ); 

    register_taxonomy( 'artist-cat', array('artist'), array(
        'hierarchical' => true, 
        'label' => __('Artist Category'), 
        'singular_label' => __('Artist Category'), 
        'rewrite' => array( 'slug' => 'artist-cat', 'with_front'=> false )
        )
    );
    register_taxonomy_for_object_type( 'artist-cat', 'artist' );

}

// Loc danh muc post type
function restrict_artist_by_category() {
    global $typenow;
    $post_type = 'artist'; // thay doi   
    $taxonomy = 'artist-cat'; // thay doi    
    if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("All {$info_taxonomy->label}"),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
            'hierarchical' => true,
        ));
    };
}

add_action('restrict_manage_posts', 'restrict_artist_by_category');

function convert_id_to_term_in_query_filter_by_artist_category($query) {
    global $pagenow;
    $post_type = 'artist'; // thay doi 
    $taxonomy = 'artist-cat'; // thay doi 
    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}
add_filter('parse_query', 'convert_id_to_term_in_query_filter_by_artist_category');

// add columns featured image
add_filter('manage_artist_posts_columns', 'vina_columns_artist_head');
add_action('manage_artist_posts_custom_column', 'vina_columns_artist_content', 10, 2);

function vina_columns_artist_head($columns) {
    $new = array();
    foreach($columns as $key => $title) {
        if ( $key == 'title' )
            $new['featured_column']  = __('Image','custom');
        if ( $key == 'date' ):
            $new['category_column']  = __('Category','custom');
        endif;
        $new[$key] = $title;
    }
    return $new;
}
 
function vina_columns_artist_content($column_name, $post_ID) {
    global $post;
    if ($column_name == 'featured_column') {       
        ?>
            <?php if( get_the_post_thumbnail_url($post_ID) ): ?>
                <img src="<?php echo get_the_post_thumbnail_url($post_ID); ?>" width="50" height="50" style="object-fit: cover;">
            <?php else: ?>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/placeholder.jpg" width="50" height="50" style="object-fit: cover;">
            <?php endif; ?>
        <?php     
    }
    if ($column_name == 'category_column') { 
        $cats = get_the_terms( $post_ID, 'artist-cat' );
        $count = count($cats);  
        $i = 1; foreach( $cats as $cat ):
            ?>
                <a href="<?php echo get_term_link( $cat ); ?>"><?php echo $cat->name; ?></a>
                <?php if( $i < $count ): ?>
                    <span>, </span>
                <?php endif; ?>
            <?php
        $i++; endforeach;     
    } 
}

// order custom post type
function vina_post_type_artist_admin_order( $wp_query ) {
  if (is_admin()) {
    // Get the post type from the query
    $post_type = $wp_query->query['post_type'];
    if ( $post_type == 'artist' || $post_type == 'partner' ) {
        $wp_query->set('orderby', 'date');
        $wp_query->set('order', 'DESC');
    }
  }
}
add_filter('pre_get_posts', 'vina_post_type_artist_admin_order');