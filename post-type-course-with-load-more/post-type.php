<?php 
// add new post type
add_action( 'init', 'post_type_new_register' );   

function post_type_new_register() {   

    $labels_1 = array( 
        'name' => __('Khóa học', 'custom_posttype'), 
        'singular_name' => __('Khóa học', 'custom_posttype'),
        'add_new' => __('Thêm mới', 'custom_posttype'), 
        'add_new_item' => __('Thêm mới','custom_posttype'), 
        'edit_item' => __('Sửa khóa học','custom_posttype'), 
        'new_item' => __('Thêm mới','custom_posttype'), 

        'view_item' => __('Xem khóa học','custom_posttype'), 
        'search_items' => __('Tìm khóa học','custom_posttype'), 
        'not_found' => __('Tìm kiếm','custom_posttype'), 
        // 'not_found_in_trash' => __('Không có gì trong Thùng rác','custom_posttype'), 
        'parent_item_colon' => ''
    );   

    $args_1 = array( 
        'labels' => $labels_1, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true, 
        'menu_icon' => 'dashicons-analytics', 
        'rewrite' => array( 'slug' => 'khoa-hoc', 'with_front'=> false ), 
        'capability_type' => 'post',
        'hierarchical' => true,
        'has_archive' => false,  
        'menu_position' => null, 
        'supports' => array('title','editor','excerpt','revisions','thumbnail','author')
    );
    register_post_type( 'khoa-hoc' , $args_1 ); 

    register_taxonomy( 'danh-muc-khoa-hoc', array('khoa-hoc'), array(
        'hierarchical' => true, 
        'label' => __('Danh mục khóa học','custom_posttype'), 
        'singular_label' => __('Danh mục khóa học','custom_posttype'), 
        'rewrite' => array( 'slug' => 'danh-muc-khoa-hoc', 'with_front'=> false )
        )
    );
    register_taxonomy_for_object_type( 'danh-muc-khoa-hoc', 'khoa-hoc' );
}

// add setting course ------------------------------------------------------------------------------------------
// Loc danh muc post type
function restrict_course_by_category() {
    global $typenow;
    $post_type = 'khoa-hoc'; // thay doi   
    $taxonomy  = 'danh-muc-khoa-hoc'; // thay doi    
    if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Hiển thị {$info_taxonomy->label}"),
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
add_action('restrict_manage_posts', 'restrict_course_by_category');

function convert_id_to_term_in_query_filter_course_by_category($query) {
    global $pagenow;
    $post_type = 'khoa-hoc'; // thay doi 
    $taxonomy  = 'danh-muc-khoa-hoc'; // thay doi 
    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}
add_filter('parse_query', 'convert_id_to_term_in_query_filter_course_by_category');

// add columns featured image
add_filter('manage_khoa-hoc_posts_columns', 'custom_posttype_columns_course_head');
add_action('manage_khoa-hoc_posts_custom_column', 'custom_posttype_columns_course_content', 10, 2);

function custom_posttype_columns_course_head($columns) {
    $new = array();
    foreach($columns as $key => $title) {
        if ( $key == 'title' )
            $new['featured_column']  = __('Ảnh đại diện','custom_posttype');
        if ( $key == 'date' ):
            $new['category_column']  = __('Danh mục khóa học','custom_posttype');
        endif;     
        $new[$key] = $title;
    }
    return $new;
}
 
function custom_posttype_columns_course_content($column_name, $post_ID) {
    global $post;
    if ($column_name == 'featured_column') {       
        ?>
            <img src="<?php echo get_the_post_thumbnail_url($post_ID); ?>" width="50" height="50" style="object-fit: contain;">
        <?php     
    }
    if ($column_name == 'category_column') { 
        $cats = get_the_terms( $post_ID, 'danh-muc-khoa-hoc' );
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

// order custom_posttype post type
function custom_posttype_post_type_new_admin_order( $wp_query ) {
    if (is_admin()) {
        // Get the post type from the query
        $post_type = $wp_query->query['post_type'];
        if ( $post_type == 'khoa-hoc' ) {
            $wp_query->set('orderby', 'date');
            $wp_query->set('order', 'DESC');
        }
    }
}
add_filter('pre_get_posts', 'custom_posttype_post_type_new_admin_order');