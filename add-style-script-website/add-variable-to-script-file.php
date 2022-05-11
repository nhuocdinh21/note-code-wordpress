<?php 
//load less js
function load_LESS() {
	$path = get_stylesheet_directory_uri() . '/assets/js/';
	?>
		<script type='text/javascript' src="<?php echo $path; ?>less.min.js"></script>
	<?php
}
add_action( 'wp_head', 'load_LESS');

//add fontello
function fontello() {
	wp_enqueue_style( 'fontello', get_stylesheet_directory_uri() . '/assets/font-icons/css/fontello.css' );
}
add_action( 'wp_enqueue_scripts', 'fontello' );

// add style common
function website_enqueue_style() {
	// add owl
	// wp_enqueue_style( 'owl_css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', 'all' );
	// wp_enqueue_style( 'owltheme_css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', 'all' );
	// add fancybox	
	// wp_enqueue_style( 'fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', 'all' );
	// load css common
	wp_enqueue_style( 'general', get_stylesheet_directory_uri() . '/assets/css/general.less' );
}
if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'website_enqueue_style' );

function add_attributes_to_all_styles( $html, $handle ) {

    // add style handles to the array below
    $styles = array(
        'general',
    );

    foreach ( $styles as $style ) {
        if ( $style === $handle ) {
            return str_replace( "rel='stylesheet'", "rel='stylesheet/less'", $html );
        }
    }

    return $html;
}
add_filter( 'style_loader_tag', 'add_attributes_to_all_styles', 10, 2 );

// Enqueue scripts
function website_enqueue_scripts() {
	// add owl
	// wp_enqueue_script( 'owl_js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'));
	// add fancybox
	// wp_enqueue_script( 'fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array('jquery'));

	// add common
	wp_enqueue_script( 'common_js', get_stylesheet_directory_uri() . '/assets/js/common.js', array('jquery'));

	wp_localize_script( 'common_js', 'custom_params', array(
		'ajaxurl'      => site_url() . '/wp-admin/admin-ajax.php',
		'theme_url'     => get_stylesheet_directory_uri(),
	) );

	wp_enqueue_script( 'common_js' );
}
add_action( 'wp_footer', 'website_enqueue_scripts' );