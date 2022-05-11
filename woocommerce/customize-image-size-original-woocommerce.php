<?php
// customize size image woocommerce --------------------------------------------------------------------------------------------------------------------------- 
add_filter( 'woocommerce_gallery_image_size', function( $size ) {
    return 'full';
} );

add_filter( 'single_product_archive_thumbnail_size', function( $size ) {
    return 'full';
} );