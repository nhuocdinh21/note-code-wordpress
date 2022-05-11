<?php
// Add SKU next to variation ID
function display_sku_next_to_var_ids() {
    ?>
    <script type="text/javascript">
        jQuery(function($) {
            "use strict";
            $(document).on('woocommerce_variations_loaded', function(event) {
                var id = -1;
                $('.woocommerce_variation.wc-metabox').each( function(index, elem) {
                    id++;
                    var var_id = $(elem).find('#variable_sku'+id).val();
                    var var_price = $(elem).find('#variable_regular_price_'+id).val();
                    var var_sale_price = $(elem).find('#variable_sale_price'+id).val();
                    var var_status = $(elem).find('#variable_stock_status'+id).val();
                    $('h3', this).append('<input type="text" value="' + var_id + '" placeholder="Mã sản phẩm" name="variation_sku_data['+id+']" style="font-weight: 400;">');
                    $('h3', this).append('<input type="number" value="' + var_price + '" placeholder="Giá" name="variation_price_data['+id+']" style="font-weight: 400;">');
                    $('h3', this).append('<input type="number" value="' + var_sale_price + '" placeholder="Giá khuyến mãi" name="variation_sale_price_data['+id+']" style="font-weight: 400;">');
                    if( var_status == 'instock' ){
                        $('h3', this).append('<select name="variation_status_data['+id+']"><option value="instock" selected>Còn hàng</option><option value="outofstock">Hết hàng</option><option value="onbackorder">Chờ hàng</option></select>');
                    }
                    else if( var_status == 'outofstock' )
                    {
                        $('h3', this).append('<select name="variation_status_data['+id+']"><option value="instock">Còn hàng</option><option value="outofstock" selected>Hết hàng</option><option value="onbackorder">Chờ hàng</option></select>');
                    }
                    else {
                        $('h3', this).append('<select name="variation_status_data['+id+']"><option value="instock">Còn hàng</option><option value="outofstock">Hết hàng</option><option value="onbackorder" selected>Chờ hàng</option></select>');
                    }
                });
            });
        });
    </script>
    <?php
}
add_action( 'admin_print_scripts', 'display_sku_next_to_var_ids', 999);

// regular variable products
add_action( 'woocommerce_save_product_variation', 'save_product_variation', 20, 2 );

/*
 * Save extra meta info for variable products
 *
 * @param int $variation_id
 * @param int $i
 * return void
 */
function save_product_variation( $variation_id, $i ){

    // save custom data
    if ( isset( $_POST['variation_sku_data'][$i] ) ) {
        // sanitize data in way that makes sense for your data type
        $custom_data = ( trim( $_POST['variation_sku_data'][$i]  ) === '' ) ? '' : sanitize_title( $_POST['variation_sku_data'][$i] );
        update_post_meta( $variation_id, '_sku', $custom_data );
    }

    if ( isset( $_POST['variation_price_data'][$i] ) ) {
        // sanitize data in way that makes sense for your data type
        $custom_data = ( trim( $_POST['variation_price_data'][$i]  ) === '' ) ? '' : sanitize_title( $_POST['variation_price_data'][$i] );
        update_post_meta( $variation_id, '_regular_price', $custom_data );
    }
    if ( isset( $_POST['variation_sale_price_data'][$i] ) ) {
        // sanitize data in way that makes sense for your data type
        $custom_data = ( trim( $_POST['variation_sale_price_data'][$i]  ) === '' ) ? '' : sanitize_title( $_POST['variation_sale_price_data'][$i] );
        update_post_meta( $variation_id, '_sale_price', $custom_data );
    }

    if ( isset( $_POST['variation_status_data'][$i] ) ) {
        // sanitize data in way that makes sense for your data type
        $custom_data = ( trim( $_POST['variation_status_data'][$i]  ) === '' ) ? '' : sanitize_title( $_POST['variation_status_data'][$i] );
        update_post_meta( $variation_id, '_stock_status', $custom_data );
    }

}