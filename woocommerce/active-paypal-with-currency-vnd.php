<?php
/*Step 1 Code to use VND currency to display Viet Nam Dong in WooCommerce:*/
add_filter( 'woocommerce_currencies', 'add_vnd_currency' );  
function add_vnd_currency( $currencies ) {  
	$currencies['VND'] = __( 'Viet Nam Dong', 'woocommerce' );  
	return $currencies;  
}
/*Step 2 Code to add VND currency symbol in WooCommerce:*/
add_filter('woocommerce_currency_symbol', 'add_vnd_currency_symbol', 10, 2);  
function add_vnd_currency_symbol( $currency_symbol, $currency ) {  
	switch( $currency ) {  
		case 'VND': $currency_symbol = 'VND'; break;  
	}  
	return $currency_symbol;  
}  
add_filter( 'woocommerce_paypal_supported_currencies', 'add_vnd_paypal_valid_currency' );       
function add_vnd_paypal_valid_currency( $currencies ) {    
	array_push ( $currencies , 'VND' );  
	return $currencies;    
}   
/*Step 3 – Code to change 'VND' currency to ‘USD’ before checking out with Paypal through WooCommerce:*/
add_filter('woocommerce_paypal_args', 'convert_vnd_to_usd', 11 );  
function convert_vnd_to_usd($paypal_args){
	if ( $paypal_args['currency_code'] == 'VND'){  
		$convert_rate = 20000; //Set converting rate
		$paypal_args['currency_code'] = 'USD'; //change VND to USD  
		$i = 1;  
        
		while (isset($paypal_args['amount_' . $i])) {  
			$paypal_args['amount_' . $i] = round( $paypal_args['amount_' . $i] / $convert_rate, 2);
			++$i;  
        }  
		if ( $paypal_args['shipping_1'] > 0 ) {
			$paypal_args['shipping_1'] = round( $paypal_args['shipping_1'] / $convert_rate, 2);
		}
		
		if ( $paypal_args['discount_amount_cart'] > 0 ) {
			$paypal_args['discount_amount_cart'] = round( $paypal_args['discount_amount_cart'] / $convert_rate, 2);
		}
		if ( $paypal_args['tax_cart'] > 0 ) {
			$paypal_args['tax_cart'] = round( $paypal_args['tax_cart'] / $convert_rate, 2);
		}
	}
return $paypal_args;  
} 