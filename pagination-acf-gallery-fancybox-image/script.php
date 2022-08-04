<?php 
// add style common
function website_enqueue_style() {
	// add fancybox	
	wp_enqueue_style( 'fancybox_css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', 'all' );
}
if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'website_enqueue_style' );

// Enqueue scripts
function website_enqueue_scripts() {
	// add fancybox	
	wp_enqueue_script( 'fancybox_js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array('jquery'));
	// add pagination	
	wp_enqueue_script( 'pagination_js', 'https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.js', array('jquery'));
}
add_action( 'wp_footer', 'website_enqueue_scripts' );