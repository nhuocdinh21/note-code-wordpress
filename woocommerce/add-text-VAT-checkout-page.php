<?php
// add text VAT-----------------------------------------------------------------------------
add_filter( 'woocommerce_cart_totals_order_total_html', 'custom_total_message_html', 10, 1 );
function custom_total_message_html( $value ) {
    if( is_checkout() )
        $value .= '<div class="note_vat">'. get_field('note_vat','option') . '</div>';

    return $value;
}