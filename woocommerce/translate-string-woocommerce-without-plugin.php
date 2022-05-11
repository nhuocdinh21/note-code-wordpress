<?php
// translate text woocommerce --------------------------------------------------------------------------------------------------------------------------- 
function translations_text_woocommerce( $strings ) {
    $text = array(
        'Quick View' => 'Xem nhanh',
        'SHOPPING CART' => 'Giỏ hàng',
        'CHECKOUT DETAILS' => 'Thanh toán',
        'ORDER COMPLETE' => 'Hoàn thành'
    );
    $strings = str_ireplace( array_keys( $text ), $text, $strings );
    return $strings;
}
add_filter( 'gettext', 'translations_text_woocommerce', 20 );