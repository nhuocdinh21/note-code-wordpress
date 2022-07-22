<?php 
	$dates = DateTime::createFromFormat('d/m/Y', get_field('thoi_gian_su_kien', get_the_ID()));

    $current_lang = pll_current_language();
    if( $current_lang == 'vi' ):
        $formatter = new IntlDateFormatter('vi_VN', IntlDateFormatter::FULL, IntlDateFormatter::FULL, date_default_timezone_get(), IntlDateFormatter::GREGORIAN, 'EEEE');
    else:
        $formatter = new IntlDateFormatter('en_US', IntlDateFormatter::FULL, IntlDateFormatter::FULL, date_default_timezone_get(), IntlDateFormatter::GREGORIAN, 'EEEE');
    endif;                                       

    $date_language = $formatter->format($dates);
?>