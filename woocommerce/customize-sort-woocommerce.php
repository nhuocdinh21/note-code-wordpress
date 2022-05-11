<?php
// change default sorting woocommerce -------------------------------------------------------------------------------------------------------
add_filter( 'woocommerce_catalog_orderby', 'custom_change_default_sorting_options' );
function custom_change_default_sorting_options( $options ){

    unset( $options[ 'rating' ] );

    $options[ 'price' ] = __('Price: Ascending','custom');
    $options[ 'price-desc' ] = __('Price: Descending','custom');

    $options['title'] = __('Name: A - Z','custom');
    $options['title-desc'] = __('Name: Z - A','custom');

    return $options;

}

add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_product_sorting' );
function custom_product_sorting( $args ) {

    // Sort a - z
    if ( isset( $_GET['orderby'] ) && 'title' === $_GET['orderby'] ) {
        $args['orderby'] = 'title';
        $args['order'] = 'asc';
    }

    // Sort z - a
    if ( isset( $_GET['orderby'] ) && 'title-desc' === $_GET['orderby'] ) {
        $args['orderby'] = 'title';
        $args['order'] = 'desc';
    }   

    return $args;
}
