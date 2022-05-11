<?php
// redirect after submit form ----------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_footer', 'redirect_after_submit_form' );
function redirect_after_submit_form(){
    ?>
        <script type="text/javascript">
            document.addEventListener( 'wpcf7mailsent', function( event ) {
                if ( '<?php echo get_field('chon_form_thong_tin_du_toan_he_thong_doanh_nghiep','option')[0]; ?>' == event.detail.contactFormId ) {
                    location = '<?php echo get_field('chon_trang_ket_qua_du_toan_he_thong_doanh_nghiep','option'); ?>?city=<?php echo preg_replace('/\s/','+', $_GET['city']); ?>&type=<?php echo preg_replace('/\s/','+', $_GET['type']); ?>&person=<?php echo preg_replace('/\s/','+', $_GET['person']); ?>';
                }
                if ( '<?php echo get_field('chon_form_thong_tin_du_toan_ho_gia_dinh','option')[0]; ?>' == event.detail.contactFormId ) {
                    location = '<?php echo get_field('chon_trang_ket_qua_du_toan_ho_gia_dinh','option'); ?>?city=<?php echo preg_replace('/\s/','+', $_GET['city']); ?>&equipment=<?php echo preg_replace('/\s/','+', $_GET['equipment']); ?>&type=<?php echo preg_replace('/\s/','+', $_GET['type']); ?>&person=<?php echo preg_replace('/\s/','+', $_GET['person']); ?>';
                }
            }, false );
        </script>
    <?php
}