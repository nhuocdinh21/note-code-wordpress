<?php
// add function removeElementWithValue
function removeElementWithValue($array, $key, $value){
    foreach($array as $subKey => $subArray){
        if($subArray[$key] == $value){
            unset($array[$subKey]);
        }
    }
    return $array;
}
// add ajax update_follow_artist -------------------------------------------------------------------------------------------------------
add_action('wp_ajax_nopriv_update_follow_artist', 'function_update_follow_artist');
add_action('wp_ajax_update_follow_artist', 'function_update_follow_artist');
function function_update_follow_artist(){
    $user_id = $_POST['user_id'];
    $postid = $_POST['postid'];
    $username = $_POST['username'];

    $list_follow = get_field('list_follow_artists', $postid);

    $field_key = 'field_6125e8f09ea07';

    if( get_field('list_follow_artists', $postid) ):
        if (array_search($user_id , array_column(get_field('list_follow_artists', $postid), 'id')) !== FALSE):
            $value = removeElementWithValue($list_follow, 'id', $user_id);
            update_field( $field_key, $value, $postid );

            $status = 0;
            $check = '111';
        else:
            $new_value = array(
                'id' => $user_id, 
                'username' => $username
            );
            array_push($list_follow, $new_value);
            update_field( $field_key, $list_follow, $postid );

            $status = 1;
            $check = '222';
        endif;        
    else:
        $value[] = array('id' => $user_id, 'username' => $username);
        update_field( $field_key, $value, $postid );

        $status = 1;
        $check = '333';
    endif;

    $follow = get_field('list_follow_artists', $postid);

    $count = count($follow);
    
    $content = array(
        'status' => $status,
        'list'   => $follow,
        'check'  => $check,
        'count'  => $count
    );
    wp_send_json($content);
    die();
}