<?php 

// Enqueue scripts
function website_enqueue_scripts() {
	wp_enqueue_script( 'lodash_js', 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js', array('jquery'));
	wp_enqueue_script( 'list_js', 'https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js', array('jquery'));
	wp_enqueue_script( 'list_pagination', 'https://cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js', array('jquery'));

	wp_enqueue_script( 'search_document', get_stylesheet_directory_uri() . '/assets/js/search_document.js', array('jquery'));		
}
add_action( 'wp_footer', 'website_enqueue_scripts' );