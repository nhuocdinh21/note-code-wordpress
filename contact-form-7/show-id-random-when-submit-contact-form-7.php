<?php
// shortcode add to form [dynamictext orderid class:hidden "RZH_CF7_GEN_NOMOR"]

function rzh_cf7_GenNomor() {
	$panjang = 5; // Length number generated
	$karakter = "0123456789"; // random character
  	for ($p = 0; $p < $panjang; $p++) {
    	$string .= $karakter[mt_rand(0,strlen($karakter)-1)];
  	}
  	return $string;
}
add_shortcode('RZH_CF7_GEN_NOMOR', 'rzh_cf7_GenNomor');