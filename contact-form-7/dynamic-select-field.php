<?php
// add dynamic select field values cf7 ----------------------------------------------------------------------------------------------------------------------------
function dynamic_select_seminor ( $scanned_tag, $replace ) {  
  
    if ( $scanned_tag['name'] != 'your-seminor' )  
        return $scanned_tag; 
  
    if ( ! have_rows('danh_sach_chu_de_hoi_thao','option') )  
        return $scanned_tag;

    // $scanned_tag['raw_values'][] = __('Subject of interest','custom') . ' *';
    while ( have_rows('danh_sach_chu_de_hoi_thao','option') ) : the_row();  
        $scanned_tag['raw_values'][] = get_sub_field('chu_de') . '|' . get_sub_field('chu_de');
    endwhile;

    $pipes = new WPCF7_Pipes($scanned_tag['raw_values']);

    $scanned_tag['values'] = $pipes->collect_befores();
    $scanned_tag['pipes'] = $pipes;
  
    return $scanned_tag;  
}  
add_filter( 'wpcf7_form_tag', 'dynamic_select_seminor', 10, 2); 

// add dynamic select field values cf7 ----------------------------------------------------------------------------------------------------------------------------
function dynamic_select_courseregister ( $scanned_tag, $replace ) {  
  
    if ( $scanned_tag['name'] != 'your-courseregister' )  
        return $scanned_tag;       

    $catechild = get_terms( 'education-cat', array(
        'orderby'    => 'menu_order',
        'order'      =>'ASC',
        'hide_empty' => 0,          
        'parent'     => 0,
    ) );

    if( $catechild ):
        foreach( $catechild as $cat ):
            $scanned_tag['raw_values'][] = '0' . '|' . $cat->name;
            $scanned_tag['values'][] = '0';
            $scanned_tag['labels'][] = $cat->name;

            $the_query = new WP_Query( $args = array(
                'post_type'      => 'dao-tao',
                'posts_per_page' => '-1',
                'orderby'        => 'meta_value',
                'meta_key'       => 'khai_giang_chi_tiet_dao_tao_thang_nam',
                'order'          => 'DESC',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'education-cat',
                        'field'    => 'term_id',
                        'terms'    =>  $cat->term_id,
                    )
                ),
                'meta_query'     => array(
                    array(
                        'key'     => 'khai_giang_chi_tiet_dao_tao_thang_nam',
                        'value'   => date('Ymd'),
                        'compare' => '>='
                    )
                ),
            ) );

            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                    $title = get_the_title() . ' - ' . __('Opening day','custom') . ': ' . date('d/m/Y', strtotime(get_field('khai_giang_chi_tiet_dao_tao_thang_nam')));
                    $scanned_tag['raw_values'][] = $title . '|' . $title;
                    $scanned_tag['values'][] = $title;
                    $scanned_tag['labels'][] = $title;
                endwhile;
            endif; wp_reset_query();
        endforeach;
    else:
        $the_query = new WP_Query( $args = array(
            'post_type'      => 'dao-tao',
            'posts_per_page' => '-1',
            'orderby'        => 'meta_value',
            'meta_key'       => 'khai_giang_chi_tiet_dao_tao_thang_nam',
            'order'          => 'DESC',
            'meta_query'     => array(
                array(
                    'key'     => 'khai_giang_chi_tiet_dao_tao_thang_nam',
                    'value'   => date('Ymd'),
                    'compare' => '>='
                )
            ),
        ) );

        if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) : $the_query->the_post();
                $title = get_the_title() . ' - ' . __('Opening day','custom') . ': ' . date('d/m/Y', strtotime(get_field('khai_giang_chi_tiet_dao_tao_thang_nam')));
                $scanned_tag['raw_values'][] = $title . '|' . $title;
            endwhile;
        endif; wp_reset_query();
    endif;

    $pipes = new WPCF7_Pipes($scanned_tag['raw_values']);

    $scanned_tag['values'] = $pipes->collect_befores();
    $scanned_tag['pipes'] = $pipes;
  
    return $scanned_tag;  

}  
add_filter( 'wpcf7_form_tag', 'dynamic_select_courseregister', 10, 2); 

// add dynamic select field values cf7 ----------------------------------------------------------------------------------------------------------------------------
function dynamic_select_coursebrochure ( $scanned_tag, $replace ) {  
  
    if ( $scanned_tag['name'] != 'your-coursebrochure' )  
        return $scanned_tag;       

    $catechild = get_terms( 'education-cat', array(
        'orderby'    => 'menu_order',
        'order'      =>'ASC',
        'hide_empty' => 0,          
        'parent'     => 0,
    ) );

    $scanned_tag['raw_values'][] = '' . '|' . __('Course you are interested in','custom');
    $scanned_tag['values'][] = '';
    $scanned_tag['labels'][] = __('Course you are interested in','custom');

    if( $catechild ):
        foreach( $catechild as $cat ):
            $scanned_tag['raw_values'][] = '0' . '|' . $cat->name;
            $scanned_tag['values'][] = '0';
            $scanned_tag['labels'][] = $cat->name;

            $the_query = new WP_Query( $args = array(
                'post_type'      => 'dao-tao',
                'posts_per_page' => '-1',
                'orderby'        => 'meta_value',
                'meta_key'       => 'khai_giang_chi_tiet_dao_tao_thang_nam',
                'order'          => 'DESC',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'education-cat',
                        'field'    => 'term_id',
                        'terms'    =>  $cat->term_id,
                    )
                ),
                'meta_query'     => array(
                    array(
                        'key'     => 'khai_giang_chi_tiet_dao_tao_thang_nam',
                        'value'   => date('Ymd'),
                        'compare' => '>='
                    )
                ),
            ) );

            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                    $title = get_the_title() . ' - ' . __('Opening day','custom') . ': ' . date('d/m/Y', strtotime(get_field('khai_giang_chi_tiet_dao_tao_thang_nam')));
                    $scanned_tag['raw_values'][] = $title . '|' . $title;
                    $scanned_tag['values'][] = $title;
                    $scanned_tag['labels'][] = $title;
                endwhile;
            endif; wp_reset_query();
        endforeach;
    else:
        $the_query = new WP_Query( $args = array(
            'post_type'      => 'dao-tao',
            'posts_per_page' => '-1',
            'orderby'        => 'meta_value',
            'meta_key'       => 'khai_giang_chi_tiet_dao_tao_thang_nam',
            'order'          => 'DESC',
            'meta_query'     => array(
                array(
                    'key'     => 'khai_giang_chi_tiet_dao_tao_thang_nam',
                    'value'   => date('Ymd'),
                    'compare' => '>='
                )
            ),
        ) );

        if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) : $the_query->the_post();
                $title = get_the_title() . ' - ' . __('Opening day','custom') . ': ' . date('d/m/Y', strtotime(get_field('khai_giang_chi_tiet_dao_tao_thang_nam')));
                $scanned_tag['raw_values'][] = $title . '|' . $title;
            endwhile;
        endif; wp_reset_query();
    endif;

    $pipes = new WPCF7_Pipes($scanned_tag['raw_values']);

    $scanned_tag['values'] = $pipes->collect_befores();
    $scanned_tag['pipes'] = $pipes;
  
    return $scanned_tag;  

}
add_filter( 'wpcf7_form_tag', 'dynamic_select_coursebrochure', 10, 2); 