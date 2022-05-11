<?php
// add languages ---------------------------------------------------------------------------------------------------------------------------------
function create_shortcode_language() {
    ob_start();
    $html = '';
    $languages = icl_get_languages('skip_missing=0');
    if(!empty($languages))
    {
    	$html.= '';
        $html.= '<li class="item_language">';
        foreach($languages as $l)
        {            
            if(!$l['active']) $html.= '<span class="languages flag_'.$l['language_code'].'"><a href="' . $l['url'] . '"><img src="' . $l['country_flag_url'] . '" alt="' . $l['language_code'] . '" /></a></span>';
            else
                $html.= '<span class="languages flag_'.$l['language_code'].'"><a href="' . $l['url'] . '"><img src="' . $l['country_flag_url'] . '" alt="' . $l['language_code'] . '" /></a></span>';            
        }
        $html.= '</li>';
        $html.= '';
    }
    $html.= '';
    ob_end_clean();
    return $html;
}
add_shortcode('languages', 'create_shortcode_language');