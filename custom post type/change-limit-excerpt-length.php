<?php
// add custom_excerpt_length ----------------------------------------------------------------------------------------------------------------------------
function custom_excerpt_length( $length ) {
    return 100;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );