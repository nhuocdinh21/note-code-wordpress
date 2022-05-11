<?php
// add columns phone to user admin table -------------------------------------------------------------------------------------------------------
function vina_new_modify_user_table( $columns ) {

    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'username' => __('Username','custom'),
        'email' => __('Email','custom'),
        'phone' => __('Phone','custom'),
        'option' => __('Option','custom'),
        'role' => __('Role','custom'),
        'products' => __('Total products','custom'),
        'status' => __('Status','custom'),
        'action' => __('Active','custom'),
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
        case 'phone' :
            return '<a href="tel:'.get_user_meta( $user_id, 'billing_phone', true ).'">'.get_user_meta( $user_id, 'billing_phone', true ).'</a>';
            break;
        case 'option' :
            $user_option = get_field('option', 'user_'.$user_id);
            if( $user_option == '1' ):
                $option_txt = __('Artist/photographer/Designer','custom');
            elseif( $user_option == '2' ):
                $option_txt = __('Galleries','custom');
            elseif( $user_option == '3' ):
                $option_txt = __('Cultural & Creative Hubs ','custom');
            elseif( $user_option == '4' ):
                $option_txt = __('Shops','custom');
            elseif( $user_option == '5' ):
                $option_txt = __('Others','custom');
            else:
                $option_txt = '';
            endif;
            return $option_txt;
            break;
        case 'products' :
            $args = array(
                'post_type'      => 'product',
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
                return '<span style="color: red;">'.__('Deactivate','option').'</span>';
            else:
                return '<span style="color: #2271b1;">'.__('Activated','option').'</span>';
            endif;
            break;
        case 'action':
            if( user_can( $user_id, 'administrator' ) != 1 ):
                $active = get_user_meta( $user_id, 'active_account', true );
                $action_html = '';
                if( $active == 1 ):
                    $action_html .= '<span><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="1" checked type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">'.__('Deactivate','option').'</label></span>';
                    $action_html .= '<span style="margin-left: 7px;"><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="0" type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">'.__('Activated','custom').'</label></span>';
                else:
                    $action_html .= '<span><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="1" type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">'.__('Deactivate','option').'</label></span>';
                    $action_html .= '<span style="margin-left: 7px;"><input data-user="'.$user_id.'" name="active_account_'.$user_id.'" value="0" checked type="radio" class="active_account"><label style="vertical-align: text-bottom;" for="active_account">'.__('Activated','custom').'</label></span>';
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