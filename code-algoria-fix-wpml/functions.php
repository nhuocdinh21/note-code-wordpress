<?php
// add price to ajax search
add_filter( 'algolia_post_product_shared_attributes', 'devvn_add_price_to_algolia', 10, 2 );
add_filter( 'algolia_searchable_product_shared_attributes', 'devvn_add_price_to_algolia', 10, 2 );
function devvn_add_price_to_algolia($shared_attributes, $post){
    $product = wc_get_product( $post );
    $shared_attributes['price_html'] = $product->get_price_html();
    return $shared_attributes;
}

require_once 'algolia/algolia_functions.php';