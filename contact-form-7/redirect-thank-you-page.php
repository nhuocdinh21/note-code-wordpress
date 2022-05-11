<?php
// add redirect thank you page  -------------------------------------------------------------------------------------------------------
add_action( 'wp_footer', 'custom_wp_footer' );
  
function custom_wp_footer() {
    ?>
        <script>
            document.addEventListener( 'wpcf7mailsent', function( event ) {
                location = '<?php echo get_field('lien_ket_trang_cam_on','option'); ?>';
            }, false );
        </script>
    <?php
}