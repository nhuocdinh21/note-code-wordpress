<?php 
//Limit Search to Post Titles Only -------------------------------------------------------------------------------------------------------
function ni_search_by_title_only( $search, $wp_query )
{
    global $wpdb;
    if ( empty( $search ) )
        return $search; // skip processing - no search term in query
    $q = $wp_query->query_vars;
    $n = ! empty( $q['exact'] ) ? '' : '%';
    $search =
    $searchand = '';
    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( like_escape( $term ) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}
add_filter( 'posts_search', 'ni_search_by_title_only', 500, 2 );

// fix lag uxbuilder -------------------------------------------------------------------------------------------------------
add_action('admin_head', function () { ?>
    <style type='text/css'>
        .iframe-frame {
            box-shadow: unset !important;
        }
    </style>
<?php });

// filter post by ID -------------------------------------------------------------------------------------------------------
add_action( 'parse_request', 'idsearch' );
function idsearch( $wp ) {
    global $pagenow;

    // If it's not the post listing return
    if( 'edit.php' != $pagenow )
        return;

    // If it's not a search return
    if( !isset( $wp->query_vars['s'] ) )
        return;

    // If it's a search but there's no prefix, return
    // if( '#' != substr( $wp->query_vars['s'], 0, 1 ) )
    //     return;

    // Validate the numeric value
    // $id = absint( substr( $wp->query_vars['s'], 1 ) );
    $id = absint( $wp->query_vars['s'] );
    if( !$id )
        return; // Return if no ID, absint returns 0 for invalid values

    // If we reach here, all criteria is fulfilled, unset search and select by ID instead
    unset( $wp->query_vars['s'] );
    $wp->query_vars['p'] = $id;
}

// add lost password form login -------------------------------------------------------------------------------------------------------
add_action( 'login_form_middle', 'add_lost_password_link' );
function add_lost_password_link() {
    return '<p class="login-lost-password"><a href="/wp-login.php?action=lostpassword">'.__('Quên mật khẩu','custom').'?</a></p>';
}

// redirect when login fail -------------------------------------------------------------------------------------------------------
if( !is_admin() ) add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login
function my_front_end_login_fail( $username ) {
    $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
    // if there's a valid referrer, and it's not the default log-in screen
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
        $login_page = get_field('dang_nhap','option');
        $login_page = $referrer;
        wp_redirect( $login_page . '?login=failed&user='.$username );  // let's append some information (login=failed) to the URL for the theme to use
        exit;
    }
}

// verify_username_password -------------------------------------------------------------------------------------------------------
function verify_username_password($user, $username, $password) {
    $referrer = $_SERVER['HTTP_REFERER'];
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) :
        $login_page = get_field('dang_nhap','option');
        $login_page = $referrer;  
        if ($username == "" || $password == "") {
            if( $username != '' ):
                wp_redirect($login_page . "?login=empty&user=".$username);
            else:
                wp_redirect($login_page . "?login=empty");
            endif;      
            exit;
        }
    endif;    
}
if( !is_admin() ) add_filter('authenticate', 'verify_username_password', 1, 3);

// add new field Phone on User Page -------------------------------------------------------------------------------------------------------
function vina_add_custom_user_profile_fields( $user ) {
    ?>
        <h3><?php _e('Số điện thoại', 'custom'); ?></h3>        
        <table class="form-table">
            <tr>
                <th>
                    <label for="phone"><?php _e('Số điện thoại', 'custom'); ?></label>
                </th>
                <td>
                    <input type="tel" name="user_phone" id="user_phone" value="<?php echo esc_attr( get_the_author_meta( 'user_phone', $user->ID ) ); ?>" class="regular-text" pattern="[0-9]{10}"><br>
                </td>
            </tr>
        </table>
    <?php 
}
add_action( 'show_user_profile', 'vina_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'vina_add_custom_user_profile_fields' );

function vina_save_custom_user_profile_fields( $user_id ) {    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;    
    update_user_meta( $user_id, 'user_phone', $_POST['user_phone'] );
}
add_action( 'personal_options_update', 'vina_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'vina_save_custom_user_profile_fields' );

// add columns phone to user admin table -------------------------------------------------------------------------------------------------------
function vina_new_modify_user_table( $columns ) {

    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'id' => __('ID khách hàng','custom'),
        'username' => __('Tên người dùng','custom'),
        'email' => __('Gmail','custom'),
        'phone' => __('Điện thoại','custom'),
        'role' => __('Phân quyền','custom'),
        'projects' => __('Tổng tin đã đăng','custom'),
        'status' => __('Trạng thái','custom'),
        'action' => __('Hành động','custom'),
    );
    unset($columns['cb']);
    unset($columns['username']);
    unset($columns['email']);
    unset($columns['role']);
    unset($columns['posts']);
    unset($columns['name']);
    return $new_columns + $columns; // This way your custom columns are at the end
}
add_filter( 'manage_users_columns', 'vina_new_modify_user_table' );

add_action('manage_users_custom_column', 'vina_show_phone_column_content', 10, 3);
function vina_show_phone_column_content($value, $column_name, $user_id) {
    switch ($column_name) {
        case 'id' :
            $email = get_the_author_meta( 'user_email', $user_id );
            return strstr($email, '@', true);
            break;
        case 'phone' :
            return '<a href="tel:'.get_the_author_meta( 'user_phone', $user_id ).'">'.get_the_author_meta( 'user_phone', $user_id ).'</a>';
            break;
        case 'projects' :
            $args = array(
                'post_type'      => 'du-an',
                'post_status'    => array(
                    'publish',
                    'pending'
                ),
                'posts_per_page' => '-1',
                'author'         => $user_id,
            );
            $query = new WP_Query( $args );
            $total = $query->found_posts;
            return $total;
            wp_reset_query();
            break;
        case 'status':
            $active = get_user_meta( $user_id, 'active_account', true );
            if( isset($active) && $active == 1 ):
                return '<span style="color: red; font-weight: 700;">Bị khóa</span>';
            else:
                return '';
            endif;
            break;
        case 'action':
            if( user_can( $user_id, 'administrator' ) != 1 ):
                $active = get_user_meta( $user_id, 'active_account', true );
                $action_html = '';
                if( $active == 1 ):
                    $action_html .= '<span><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="1" checked type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">Khóa tài khoản</label></span>';
                    $action_html .= '<span style="margin-left: 7px;"><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="0" type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">Kích hoạt</label></span>';
                else:
                    $action_html .= '<span><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="1" type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">Khóa tài khoản</label></span>';
                    $action_html .= '<span style="margin-left: 7px;"><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="0" checked type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">Kích hoạt</label></span>';
                endif;                
                return $action_html;
            endif;
            break;
        default:
    }
    return $value;
}

// add script vina_excute_enable_account -------------------------------------------------------------------------------------------------------
add_action( 'admin_footer', 'vina_excute_enable_account' );
function vina_excute_enable_account() { 
  ?>
    <script>
        jQuery(document).ready(function($) {
            $('input.active_account').on('change', function(e) {
                e.preventDefault();
                var value = $(this).val();
                var user_id = $(this).data('user');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
                    data: ({
                        action : 'update_enable_account',
                        value  : value,
                        user_id   : user_id,
                    }),
                    success: function(data) {
                        console.log(data);
                        window.location = document.location.href;
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

// add ajax update_enable_account -------------------------------------------------------------------------------------------------------

add_action('wp_ajax_nopriv_update_enable_account', 'function_update_enable_account');
add_action('wp_ajax_update_enable_account', 'function_update_enable_account');
function function_update_enable_account(){
    $data = array();
    $value = $_POST['value'];
    $user_id = $_POST['user_id'];

    update_user_meta( $user_id, 'active_account', $value );

    $active = get_user_meta( $user_id, 'active_account', true );

    $user = new WP_User( $user_id );
    if( $active == 1 ):
        $user->set_role( 'pedding' );
    else:
        $user->set_role( 'contributor' );
    endif;

    echo json_encode($data);
    die();
}

// change post per page for taxonomy danh-muc -------------------------------------------------------------------------------------------------------
add_action( 'pre_get_posts', function ( $q )
{
    if ( !is_admin()  && $q->is_main_query() && is_tax('danh-muc') ) 
    {
        $q->set( 'posts_per_page', get_field('number_projects','option') );
    }
  
});

// add new sidebar -------------------------------------------------------------------------------------------------------
add_action( 'widgets_init', 'vina_add_new_sidebar' );
function vina_add_new_sidebar() {
    register_sidebar( array(
        'name' => __( 'Sidebar Project Category', 'custom' ),
        'id' => 'sidebar-project-cat',
        'description' => __( 'Widgets in this area will be shown on sidebar Project Category.', 'custom' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<span class="widget-title "><span>',
        'after_title'   => '</span></span>',
    ) );

    register_sidebar( array(
        'name' => __( 'Sidebar Project Page', 'custom' ),
        'id' => 'sidebar-project-page',
        'description' => __( 'Widgets in this area will be shown on sidebar Project Page.', 'custom' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<span class="widget-title "><span>',
        'after_title'   => '</span></span>',
    ) );

    register_sidebar( array(
        'name' => __( 'Sidebar Search Project', 'custom' ),
        'id' => 'sidebar-search-project',
        'description' => __( 'Widgets in this area will be shown on sidebar Search Project.', 'custom' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<span class="widget-title "><span>',
        'after_title'   => '</span></span>',
    ) );
}

// add query cho search -------------------------------------------------------------------------------------------------------
add_action( 'pre_get_posts', 'vina_modify_query_limit_posts' ); 
function vina_modify_query_limit_posts( $query ) {
    // Check if on frontend and main query is modified
    if( !is_admin() && $query->is_main_query() ) {

        if( isset($_GET['cat']) && $_GET['cat'] != 0 )
        {
            $tax_query = $_GET['cat'];
        }

        if( $tax_query ){
            $tax_query_arr = array(array( 
                'taxonomy' => 'danh-muc', //or tag or custom taxonomy
                'field' => 'id', 
                'terms' => array($tax_query)
            ) );
        }
            

        if( isset($_GET['city']) && $_GET['city'] !=0 )
        {
            $custom_meta[]= array(
                'key' => 'city',
                'value' => $_GET['city'],
                'compare' => '='
            );
        }
        if( isset($_GET['district']) && $_GET['district'] !=0 ){
            $custom_meta[]= array(
                'key' => 'district',
                'value' => $_GET['district'],
                'compare' => '='
            );
        }
        if( isset($_GET['ward']) && $_GET['ward'] !=0 ){
            $custom_meta[]= array(
                'key' => 'ward',
                'value' => $_GET['ward'],
                'compare' => '='
            );
        }
        if( isset($_GET['street']) && $_GET['street'] !=0 ){
            $custom_meta[]= array(
                'key' => 'street',
                'value' => $_GET['street'],
                'compare' => '='
            );
        }
        if( isset($_GET['type']) && $_GET['type'] != '' ){
            $custom_meta[]= array(
                'key' => 'project_type',
                'value' => $_GET['type'],
                'compare' => 'LIKE'
            );
        }
        if( isset($_GET['price']) && $_GET['price'] != '' ){
            if( $_GET['price'] == 'desc' ){
                $custom_meta[] = array(
                    'relation'  => 'AND',
                    array(
                        'price' => array(
                            'key' => 'price',
                            'compare' => 'EXISTS'
                        )                        
                    ),
                    array(                        
                        'price_negotiate' => array(
                            'key' => 'price_negotiate',
                            'value' => '1',
                            'compare' => '!='
                        ) 
                    ),
                );

                $query->set('orderby', array('price' => 'DESC'));

            }

            elseif( $_GET['price'] == 'asc' ){
                $custom_meta[] = array(
                    'relation'  => 'AND',
                    array(
                        'price' => array(
                            'key' => 'price',
                            'compare' => 'EXISTS'
                        )                        
                    ),
                    array(                        
                        'price_negotiate' => array(
                            'key' => 'price_negotiate',
                            'value' => '1',
                            'compare' => '!='
                        ) 
                    ),
                );

                $query->set('orderby', array('price' => 'ASC'));
            }

            elseif( $_GET['price'] == 'negotiate' ){
                $custom_meta[]= array(
                    'key' => 'price_negotiate',
                    'value' => '1',
                    'compare' => '='
                );
            }
        }

        if( $custom_meta ){
            $query->set('meta_query', $custom_meta);
        }            

        if( isset($_GET['cat']) && $_GET['cat'] != 0 ){
            $query->set('tax_query', $tax_query_arr);
        }

        return;  
    } 
}

// add Resize Original Upload
add_filter('wp_handle_upload', 'max_dims_for_new_uploads', 10, 2 );

function max_dims_for_new_uploads( $array, $context ) {
    // $array = array( 'file' => $new_file, 'url' => $url, 'type' => $type )
    // $context = 'upload' || 'sideload'
    $ok = array( 'image/jpeg', 'image/gif', 'image/png' );
    if ( ! in_array( $array['type'], $ok ) ) return $array;

    $editor = wp_get_image_editor( $array['file'] );
    if ( is_wp_error( $editor ) )
        return $editor;

    $editor->set_quality( 90 );
    $editor->resize( 1100, 1100 ); // (int) max width, (int) max height[, (bool) crop]
    $editor->save( $array['file'] );
    return $array;

}

// add ajax check_project_title -------------------------------------------------------------------------------------------------------
add_action('wp_ajax_nopriv_check_project_title', 'function_check_project_title');
add_action('wp_ajax_check_project_title', 'function_check_project_title');
function function_check_project_title(){
    $title = $_POST['title'];
    $args = array(      
        'post_type'   => 'du-an',
        'title'           => $title,
        'post_status' => 'publish'    
    );
    $wp_query = new WP_Query($args);
    $count = '';
    if($wp_query->have_posts()):
        $count = 1;
    endif;

    echo json_encode($count);
    die();
}

// add ajax upload_new_project -------------------------------------------------------------------------------------------------------
add_action('wp_ajax_nopriv_upload_new_project', 'function_upload_new_project');
add_action('wp_ajax_upload_new_project', 'function_upload_new_project');
function function_upload_new_project(){
    ob_start();
        $project_type             = $_POST['project_type'];
        $project_cat              = $_POST['project_cat'];
        $project_title            = $_POST['project_title'];
        $project_price            = $_POST['project_price'];
        $project_price_negotiate  = $_POST['project_price_negotiate'];
        $project_acreage          = $_POST['project_acreage'];
        $city                     = $_POST['city'];
        $district                 = $_POST['district'];
        $ward                     = $_POST['ward'];
        $street                   = $_POST['street'];
        $address                  = $_POST['address'];
        $project_description      = $_POST['project_description'];
        $contact_name             = $_POST['contact_name'];
        $contact_phone            = $_POST['contact_phone'];
        $agency                   = $_POST['agency'];
        $time_slot                = $_POST['time_slot'];

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $allowed_roles = array('contributor');

        if( array_intersect($allowed_roles, $current_user->roles ) ):
            $status = 'pending';
        else:
            $status = 'publish';
        endif;
        
        // get cat
        $array_cat = array();
        array_push($array_cat, $project_cat);

        $catechild = get_terms( 'danh-muc', array(
            'orderby'    => 'menu_order',
            'order'      =>'ASC',
            'hide_empty' => 0,          
            'parent'     => $project_cat,
        ) );
        foreach( $catechild as $cat ):
            if (strpos($cat->slug, $project_type) !== false):
                array_push($array_cat, $cat->term_id);
            endif;
        endforeach;

        // project_price_negotiate
        if( $project_price_negotiate ):
            $project_price_negotiate_status = '1';
        else:
            $project_price_negotiate_status = '';
        endif;

        $project = array(
            'post_title'    => wp_strip_all_tags( $project_title ),
            'post_content'  => $project_description,
            'post_status'   => $status,           
            'post_type'     => 'du-an',
            'post_author'   => $user_id,
            'meta_input'    => array(
                'project_type'       => $project_type,
                'price'              => $project_price,
                'price_negotiate'    => $project_price_negotiate_status,
                'city'               => $city,
                'district'           => $district,
                'ward'               => $ward,
                'street'             => $street,
                'address'            => $address,
                'acreage'            => $project_acreage,
                'user_name'          => $contact_name,
                'user_phone'         => $contact_phone,
                'agency'             => $agency,
                'time_slot'          => $time_slot,
            ),
            'tax_input'    => array(
                'danh-muc' => $array_cat
            ),
        );
           
        $project_id = wp_insert_post( $project ); 

        wp_set_post_terms( $project_id, $array_cat, 'danh-muc' );

        if (!empty($_FILES['project_images']['name'][0])) {

            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $files = $_FILES['project_images'];
            $count = 0;
            $galleryImages = array();

            foreach ($files['name'] as $count => $value) {

                if ($files['name'][$count]) {

                    $file = array(
                        'name'     => $files['name'][$count],
                        'type'     => $files['type'][$count],
                        'tmp_name' => $files['tmp_name'][$count],
                        'error'    => $files['error'][$count],
                        'size'     => $files['size'][$count]
                    );

                    $upload_overrides = array( 'test_form' => false );
                    $upload = wp_handle_upload($file, $upload_overrides);


                    // $filename should be the path to a file in the upload directory.
                    $filename = $upload['file'];

                    // The ID of the post this attachment is for.
                    $parent_post_id = $project_id;

                    // Check the type of tile. We'll use this as the 'post_mime_type'.
                    $filetype = wp_check_filetype( basename( $filename ), null );

                    // Get the path to the upload directory.
                    $wp_upload_dir = wp_upload_dir();

                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                        'post_mime_type' => $filetype['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    if( $count == 0 ){
                        set_post_thumbnail($parent_post_id, $attach_id);
                    }

                    array_push($galleryImages, $attach_id);

                }                

                $count++;                

                // add images to the gallery field
                update_field('field_60fe14d95d446', $galleryImages, $project_id);

            }

        }

        echo $project_id;

    $result = ob_get_clean(); 
    wp_send_json($result);
    die();
}

// add ajax check_project_title_update -------------------------------------------------------------------------------------------------------
add_action('wp_ajax_nopriv_check_project_title_update', 'function_check_project_title_update');
add_action('wp_ajax_check_project_title_update', 'function_check_project_title_update');
function function_check_project_title_update(){
    $title = $_POST['title'];
    $project_id = $_POST['project_id'];
    $args = array(      
        'post_type'   => 'du-an',
        'title'           => $title,
        'post_status' => 'publish',
        'post__not_in' => array($project_id),   
    );
    $wp_query = new WP_Query($args);
    ob_start();
        $count = '';
        if($wp_query->found_posts):
            $count = 1;
        endif;
        echo $count;
        wp_reset_query();

    $result = ob_get_clean(); 
    wp_send_json($result);

    echo json_encode($result);
    die();
}

// add ajax update_project -------------------------------------------------------------------------------------------------------
add_action('wp_ajax_nopriv_update_project', 'function_update_project');
add_action('wp_ajax_update_project', 'function_update_project');
function function_update_project(){
    ob_start();
        $project_id               = $_POST['project_id'];
        $project_type             = $_POST['project_type'];
        $project_cat              = $_POST['project_cat'];
        $project_title            = $_POST['project_title'];
        $project_price            = $_POST['project_price'];
        $project_price_negotiate  = $_POST['project_price_negotiate'];
        $project_acreage          = $_POST['project_acreage'];
        $city                     = $_POST['city'];
        $district                 = $_POST['district'];
        $ward                     = $_POST['ward'];
        $street                   = $_POST['street'];
        $address                  = $_POST['address'];
        $project_description      = $_POST['project_description'];
        $contact_name             = $_POST['contact_name'];
        $contact_phone            = $_POST['contact_phone'];
        $agency                   = $_POST['agency'];
        $time_slot                = $_POST['time_slot'];

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $allowed_roles = array('contributor');

        if( array_intersect($allowed_roles, $current_user->roles ) ):
            $status = 'pending';
        else:
            $status = 'publish';
        endif;
        
        // get cat
        $array_cat = array();
        array_push($array_cat, $project_cat);

        $catechild = get_terms( 'danh-muc', array(
            'orderby'    => 'menu_order',
            'order'      =>'ASC',
            'hide_empty' => 0,          
            'parent'     => $project_cat,
        ) );
        foreach( $catechild as $cat ):
            if (strpos($cat->slug, $project_type) !== false):
                array_push($array_cat, $cat->term_id);
            endif;
        endforeach;

        // project_price_negotiate
        if( $project_price_negotiate ):
            $project_price_negotiate_status = '1';
        else:
            $project_price_negotiate_status = '';
        endif;

        $project = array(
            'ID'            => $project_id,
            'post_title'    => wp_strip_all_tags( $project_title ),
            'post_content'  => $project_description,
            'post_status'   => $status,           
            'post_type'     => 'du-an',
            'meta_input'    => array(
                'project_type'       => $project_type,
                'price'              => $project_price,
                'price_negotiate'    => $project_price_negotiate_status,
                'city'               => $city,
                'district'           => $district,
                'ward'               => $ward,
                'street'             => $street,
                'address'            => $address,
                'acreage'            => $project_acreage,
                'user_name'          => $contact_name,
                'user_phone'         => $contact_phone,
                'agency'             => $agency,
                'time_slot'          => $time_slot,
            ),
            'tax_input'    => array(
                'danh-muc' => $array_cat
            ),
        );

        $result = wp_update_post($project, true);

        wp_set_post_terms( $project_id, $array_cat, 'danh-muc' );

        if (!empty($_FILES['project_images']['name'][0])) {

            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $files = $_FILES['project_images'];
            $count = 0;
            $galleryImages = array();

            foreach ($files['name'] as $count => $value) {

                if ($files['name'][$count]) {

                    $file = array(
                        'name'     => $files['name'][$count],
                        'type'     => $files['type'][$count],
                        'tmp_name' => $files['tmp_name'][$count],
                        'error'    => $files['error'][$count],
                        'size'     => $files['size'][$count]
                    );

                    $upload_overrides = array( 'test_form' => false );
                    $upload = wp_handle_upload($file, $upload_overrides);


                    // $filename should be the path to a file in the upload directory.
                    $filename = $upload['file'];

                    // The ID of the post this attachment is for.
                    $parent_post_id = $project_id;

                    // Check the type of tile. We'll use this as the 'post_mime_type'.
                    $filetype = wp_check_filetype( basename( $filename ), null );

                    // Get the path to the upload directory.
                    $wp_upload_dir = wp_upload_dir();

                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                        'post_mime_type' => $filetype['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    if( $count == 0 ){
                        set_post_thumbnail($parent_post_id, $attach_id);
                    }

                    array_push($galleryImages, $attach_id);

                }                

                $count++;                

                // add images to the gallery field
                update_field('field_60fe14d95d446', $galleryImages, $project_id);

            }

        }
        
        echo $project_id;

    $result = ob_get_clean(); 
    wp_send_json($result);
    die();
}

// add captcha to form_login
// add_action('wp_head',function (){echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';});

// add_action('woocommerce_login_form','add_recaptcha_to_login_form');
// function add_recaptcha_to_login_form() {
//     echo '<div class="g-recaptcha" data-sitekey="6LcdHz0dAAAAAD5FB1fFJiKmXBpSYJD-eRStKR04"></div>';
// }
// add_action( "login_form", "display_login_captcha" ); 

// function verify_login_captcha($user, $password) { 
//     if (isset($_POST['g-recaptcha-response'])) { 
//         $recaptcha_secret = '6LcdHz0dAAAAALrHJlGRwC1mJL04QUq6x0FcyqR8'; 
//         $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']); 
//         $response = json_decode($response["body"], true); 
//         if (true == $response["success"]) { 
//             return $user; 
//         } else { 
//             return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot")); 
//         } 
//     } else { 
//         return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot. If not then enable JavaScript")); 
//     } 
// } 
// add_filter("wp_authenticate_user", "verify_login_captcha", 10, 2);