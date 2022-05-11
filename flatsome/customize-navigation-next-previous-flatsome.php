<?php
// Display navigation to next/previous pages when applicable ----------------------------------------------------------------------------------------------------------------------------
if ( ! function_exists( 'custom_content_nav' ) ) :
function custom_content_nav( $nav_id ) {
    global $wp_query, $post;

    // Don't print empty markup on single pages if there's nowhere to navigate.
    if ( is_single() ) {
        $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
        $next = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous )
            return;
    }

    // Don't print empty markup in archives if there's only one page.
    if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
        return;

    $nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';

    ?>
    <?php if ( is_single() ) : // navigation links for single posts ?>
    <nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
    <div class="flex-row next-prev-nav bt bb">
        <div class="flex-col flex-grow nav-prev text-left">
                <?php previous_post_link( '<div class="nav-previous">%link</div>','<span class="meta-nav" aria-hidden="true">'.__('Bài trước','custom').'</span><br><span class="post_title">%title</span>' ); ?>

        </div>
        <div class="flex-col flex-grow nav-next text-right">
                <?php next_post_link( '<div class="nav-next">%link</div>', '<span class="meta-nav" aria-hidden="true">'.__('Bài tiếp','custom').'</span><br><span class="post_title">%title</span>' ); ?>
        </div>
    </div>

    <?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

    <div class="flex-row">
        <div class="flex-col flex-grow">
   <?php if ( get_next_posts_link() ) : ?>
        <div class="nav-previous"><?php next_posts_link( __( '<span class="icon-angle-left"></span> Older posts', 'flatsome' ) ); ?></div>
        <?php endif; ?>
        </div>
        <div class="flex-col flex-grow">
          <?php if ( get_previous_posts_link() ) : ?>
             <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="icon-angle-right"></span>', 'flatsome' ) ); ?></div>
         <?php endif; ?>        </div>
    </div>
    <?php endif; ?>
    </nav><!-- #<?php echo esc_html( $nav_id ); ?> -->

    <?php
}
endif;