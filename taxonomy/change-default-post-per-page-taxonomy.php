<?php
// change default post per page for taxonomy ----------------------------------------------------------------------------------------------------------------------------
add_action( 'pre_get_posts', function ( $q )
{
    if ( !is_admin()  && $q->is_main_query() && is_tax('doi-tac') ) :
        $q->set( 'posts_per_page', 8 );
    endif;

    if ( !is_admin()  && $q->is_main_query() && is_tax('dich-vu') ) :    
        $q->set( 'posts_per_page', 6 );
    endif;

    if ( !is_admin()  && $q->is_main_query() && is_tax('du-an') ) :    
        $q->set( 'posts_per_page', 16 );
    endif; 

    if ( !is_admin()  && $q->is_main_query() && is_tax('ung-dung-giai-phap') ) :    
        $q->set( 'posts_per_page', 12 );
    endif; 

    if ( !is_admin()  && $q->is_main_query() && is_search() ):
        if ( 'du-an' === $_GET['post_type'] ) {            
            $taxquery = array(
                array(
                    'taxonomy' => 'du-an',
                    'field' => 'id',
                    'terms' => array( $_GET['cat_id'] ),
                    'operator'=> 'IN'
                )
            );

            $q->set( 'tax_query', $taxquery );
            $q->set( 'posts_per_page', 16 );
        }
    endif;
});