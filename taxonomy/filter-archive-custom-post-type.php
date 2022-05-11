<?php
// add filter archive custom post type
function justread_filter_archive( $query ) {
        if ( is_admin() ) {
                return;
        }
        if ( is_archive() ) {
                if ( 'field' === $_GET['getby'] ) {
                        $query->set( 'meta_key', 'author_book' );
                        $query->set( 'meta_value', $_GET['field'] );
                        $query->set( 'meta_compare', '=' );
                }
        }
        return $query;
}
add_action( 'pre_get_posts', ‘justread_filter_archive’);