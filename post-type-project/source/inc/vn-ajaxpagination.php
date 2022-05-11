<?php 
function ajax_pagination_svl( $atts ){
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 5,
            'paged' => 1,
            'post_type' => 'du-an'
        ), $atts,'ajax_pagination'
    );
    $posts_per_page = intval($atts['posts_per_page']);
    $paged = intval($atts['paged']);
    $post_type = sanitize_text_field($atts['post_type']);
    ob_start();
        ?>
            <div id="result_ajaxp">
                <?php query_ajax_pagination( $post_type, $posts_per_page , $paged ); ?>
            </div>
        <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('ajax_pagination', 'ajax_pagination_svl');
 
function query_ajax_pagination( $post_type = 'du-an', $posts_per_page = 5, $paged = 1){
    $user_id = get_current_user_id();
    if( current_user_can('administrator') ):
        $user_id = '';
    else:
        $user_id = $user_id;
    endif;
    $args_svl = array(
        'post_type'      => $post_type,
        'post_status'    => array(
            'publish',
            'pending'
        ),
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'author'         => $user_id,
    );
    $q_svl = new WP_Query( $args_svl );
 
    /*Tổng bài viết trong query trên*/
    $total_records = $q_svl->found_posts;
 
    /*Tổng số page*/
    $total_pages = ceil($total_records/$posts_per_page);
 
    if($q_svl->have_posts()):
        ?>
            <div class="wrap_ajax_pagination">
                <div class="count_project"><?php echo __('Số lượng','custom'); ?>: <?php echo $total_records; ?></div>
                <div class="ajax_pagination" posts_per_page="<?php echo $posts_per_page; ?>" post_type="<?php echo $post_type; ?>">
                    <table>
                        <tbody>
                            <tr>
                                <th><?php echo __('Mã tin đăng','custom'); ?></th>
                                <th><?php echo __('Tiêu đề','custom'); ?></th>
                                <th class="hide-for-small"><?php echo __('Giá','custom'); ?></th>
                                <th class="hide-for-small"><?php echo __('Cập nhật','custom'); ?></th>
                                <th><?php echo __('Trạng thái','custom'); ?></th>
                                <th><?php echo __('Lượt xem','custom'); ?></th>
                                <th><?php echo __('Hành động','custom'); ?></th>
                            </tr>                        
                            <?php while($q_svl->have_posts()):$q_svl->the_post(); ?>
                                <?php 
                                    $post_id = get_the_ID(); 

                                    $price = get_post_meta( $post_id, 'price', true );
                                    $price_negotiate = get_post_meta( $post_id, 'price_negotiate', true );

                                    $post_status = get_post_status($post_id);
                                    if( $post_status == 'publish' ):
                                        $post_status_txt = __('Đã duyệt','custom');
                                    elseif( $post_status == 'pending'):
                                        $post_status_txt = __('Đang đợi duyệt','custom');
                                    else:
                                        $post_status_txt = __('Bản nháp','custom');
                                    endif;
                                ?>
                                <tr>
                                    <td class="id_project">#<?php echo get_the_ID(); ?></td>
                                    <td class="title_project">
                                        <?php if( $post_status == 'publish' ): ?>
                                            <span><a href="<?php echo get_field('lien_ket_cap_nhat_du_an','option'); ?>?ID=<?php echo get_the_ID(); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></a></span>
                                        <?php else: ?>
                                            <span><?php echo get_the_title(); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="price_project hide-for-small">
                                        <?php if( $price_negotiate ): ?>
                                            <span class="amount"><?php echo __('Thỏa thuận','custom'); ?></span>
                                        <?php else: ?>
                                            <span class="amount"> <?php echo number_format($price, 0, ',', '.'); ?> <span class="unit">vnđ</span></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="date_project hide-for-small">
                                        <span><?php echo get_the_modified_date('d/m/Y • H:i'); ?></span>
                                    </td>
                                    <td class="status_project">
                                        <span><?php echo $post_status_txt; ?></span>
                                    </td>
                                    <td class="view_project">
                                        <span>
                                            <?php 
                                                if( get_post_field( 'total_view', $post_id ) ):
                                                    echo number_format(get_post_field( 'total_view', $post_id ), 0, ',', '.'); 
                                                else:
                                                    echo '0';
                                                endif;
                                            ?>                                                
                                        </span>
                                    </td>
                                    <td class="edit_project">
                                        <?php if( $post_status == 'publish' ): ?>
                                            <span><a href="<?php echo get_field('lien_ket_cap_nhat_du_an','option'); ?>?ID=<?php echo get_the_ID(); ?>"><?php echo __('Sửa','custom'); ?></a></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo paginate_function( $posts_per_page, $paged, $total_records, $total_pages); ?>
                <div class="loading_ajaxp">
                    <div id="circularG">
                        <div id="circularG_1" class="circularG"></div>
                        <div id="circularG_2" class="circularG"></div>
                        <div id="circularG_3" class="circularG"></div>
                        <div id="circularG_4" class="circularG"></div>
                        <div id="circularG_5" class="circularG"></div>
                        <div id="circularG_6" class="circularG"></div>
                        <div id="circularG_7" class="circularG"></div>
                        <div id="circularG_8" class="circularG"></div>
                    </div>
                </div>
            </div>
        <?php
    else:
        ?>
           <div class="no_content_projects">
               <div class="inner">
                   <div class="info text-center">
                       <p><?php echo __('Bạn chưa có tin đăng nào','custom'); ?></p>
                       <p><a href="<?php echo get_field('chon_trang_dang_tin','option'); ?>"><?php echo get_field('tieu_de_nut_dang_tin_desktop','option'); ?></a></p>
                   </div>
               </div>
           </div>
        <?php
    endif;
    wp_reset_query();
}
 
/******************
Function phân trang PHP có dạng 1,2,3 ...
 ********************/
function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
 
        $right_links = $current_page + 3;
        $previous = $current_page - 3; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link
 
        if($current_page > 1){
            $previous_link = ( $previous <= 0 ) ? 1 : $previous;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First"><i class="fas fa-angle-double-left"></i></a></li>'; //first link
            $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous"><i class="fas fa-angle-left"></i></a></li>'; //previous link
            for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                if($i > 0){
                    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                }
            }
            $first_link = false; //set first link to false
        }
 
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active"><span>'.$current_page.'</span></li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active"><span>'.$current_page.'</span></li>';
        }else{ //regular current link
            $pagination .= '<li class="active"><span>'.$current_page.'</span></li>';
        }
 
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){
            $next_link = ($i > $total_pages)? $total_pages : $i;
            $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next"><i class="fas fa-angle-right"></i></a></li>'; //next link
            $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last"><i class="fas fa-angle-double-right"></i></a></li>'; //last link
        }
 
        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}
 
/** Xử lý Ajax trong WordPress */
add_action( 'wp_ajax_LoadPostPagination', 'LoadPostPagination_init' );
add_action( 'wp_ajax_nopriv_LoadPostPagination', 'LoadPostPagination_init' );
function LoadPostPagination_init() {
    $posts_per_page = intval($_POST['posts_per_page']);
    $paged = intval($_POST['data_page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $allpost = query_ajax_pagination( $post_type, $posts_per_page , $paged );
    echo $allpost;
    exit;
}
 
add_action( 'wp_enqueue_scripts', 'pagination_script' );
function pagination_script() {
    /** Thêm js vào website */
    wp_enqueue_script( 'devvn-ajax', esc_url( trailingslashit( get_stylesheet_directory_uri() ) . '/assets/pagination/pagination.js' ), array( 'jquery' ) );
    $php_array = array(
        'admin_ajax' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script( 'devvn-ajax', 'svl_array_ajaxp', $php_array );
 
    /*Thêm css vào website*/
    wp_enqueue_style( 'ajaxp', esc_url( trailingslashit( get_stylesheet_directory_uri() )) . '/assets/pagination/pagination.css');
}
