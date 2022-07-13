<?php
// add video product
add_action( 'add_meta_boxes', 'hdevvn_add_clip_url_metaboxes' );
function hdevvn_add_clip_url_metaboxes() {
  add_meta_box(
    'hdevvn_clip_url',
    'Video Sản phẩm',
    'hdevvn_clip_url',
    'product',
    'side',
    'default'
  );
}
function hdevvn_clip_url() {
  global $post;
  wp_nonce_field( basename( __FILE__ ), 'clip_url_fields' );
  $clip_url = get_post_meta( $post->ID, 'clip_url', true );
  echo '<input type="text" name="clip_url" value="' . esc_textarea( $clip_url )  . '" class="widefat">';
}
function hdevvn_save_clip_url_meta( $post_id, $post ) {
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return $post_id;
  }
  if ( ! isset( $_POST['clip_url'] ) || ! wp_verify_nonce( $_POST['clip_url_fields'], basename(__FILE__) ) ) {
    return $post_id;
  }
  $events_meta['clip_url'] = esc_textarea( $_POST['clip_url'] );
  foreach ( $events_meta as $key => $value ) :
    if ( 'revision' === $post->post_type ) {
      return;
    }
    if ( get_post_meta( $post_id, $key, false ) ) {
      update_post_meta( $post_id, $key, $value );
    } else {
      add_post_meta( $post_id, $key, $value);
    }
    if ( ! $value ) {
      delete_post_meta( $post_id, $key );
    }
  endforeach;
}
add_action( 'save_post', 'hdevvn_save_clip_url_meta', 1, 2 );

// Function override of flatsome_wc_get_gallery_image_html
function flatsome_wc_get_gallery_image_html( $attachment_id, $main_image = false, $size = 'woocommerce_single' ) {
  global $post;
  $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
  $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
  $image_size        = apply_filters( 'woocommerce_gallery_image_size', $size );
  $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
  $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
  $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
  $image             = wp_get_attachment_image( $attachment_id, $image_size, false, array(
    'title'                   => get_post_field( 'post_title', $attachment_id ),
    'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
    'data-src'                => $full_src[0],
    'data-large_image'        => $full_src[0],
    'data-large_image_width'  => $full_src[1],
    'data-large_image_height' => $full_src[2],
    'class'                   => $main_image ? 'wp-post-image skip-lazy' : 'skip-lazy', // skip-lazy, blacklist for Jetpack's lazy load.
  ) );

  $image_wrapper_class = $main_image ? 'slide first' : 'slide';    

  if( $main_image ):
    $clip_url = get_post_meta( get_the_ID(), 'clip_url', TRUE );
    if($clip_url):
      parse_str( parse_url( $clip_url, PHP_URL_QUERY ), $my_array_of_vars );
      $image .= '<iframe width="100%" height="350" src="https://www.youtube.com/embed/'.$my_array_of_vars['v'].'" frameborder="0"></iframe>';

      $image_wrapper_class .= ' product_has_video';
    endif;
  endif;

  $image_html = $main_image ? $image : '<a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a>';
  return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image '.$image_wrapper_class.'">' . $image_html . '</div>';
}