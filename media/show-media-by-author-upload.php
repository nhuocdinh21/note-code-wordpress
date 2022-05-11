<?php
// user only preview author upload -------------------------------------------------------------------------------------------------------
add_filter( 'ajax_query_attachments_args', "user_restrict_media_library" );
function user_restrict_media_library(  $query ) {
    global $current_user;
    $query['author'] = $current_user->ID ;
    return $query;
}