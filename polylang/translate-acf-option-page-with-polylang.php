<?php if( have_rows('list_button_banner_home', 'option') ): ?>
    <?php
        while (have_rows('list_button_banner_home', 'option')) : the_row();
            $title_str = get_sub_field('title');
            $title_arr = explode(',', $title_str);
            $languages = pll_languages_list(array('fields' => array()));
            $languages_arr = array();
            foreach ($languages as $lang) :
                array_push($languages_arr, $lang->slug);
            endforeach;

            $title_list = [];
            foreach ($languages_arr as $key => $value) {
                if (isset($title_list[$value])) {
                    $title_list[$value] = $title_arr[$key];
                } else {
                    if( isset($title_arr[$key]) ):
                        $title_list[$value] = $title_arr[$key];
                    else:
                        $title_list[$value] = '';
                    endif;                                    
                }
            }

            $current_lang = pll_current_language();
            $title_button = '';
            foreach ($title_list as $key => $value):
                if( $key == $current_lang ):
                    $title_button .= $value;
                endif;
            endforeach;
        ?>
        <div class="item">
            <?php if( $title_button != '' ): ?>
                <a class="dismepanana" href="<?php echo get_sub_field('link'); ?>"
                    <?php if (get_sub_field('open_in_new_tab') == 'true'): echo 'target="_blank"'; endif; ?>>
                    <?php echo $title_button; ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>