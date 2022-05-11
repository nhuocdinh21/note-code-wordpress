<?php
// change order status payment
function vina_change_order_status( $order_id ) {
    if ( ! $order_id ) {return;}
    $order = wc_get_order( $order_id );
    if( 'on-hold'== $order->get_status() ) {
        $order->update_status( 'wc-processing' );
    }
}
add_action('woocommerce_thankyou','vina_change_order_status');