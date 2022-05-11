<?php
/**
	* Plugin Name: Product Extra Price
	* Description: Calculator Price Extra Field Woocommerce
	* Version: 1.0.0
	* Author: Levano.vn
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display custom field on the front end
 * @since 1.0.0
 */
function levano_display_field_product_extra() {
	global $post;
	// Check for the custom field value
	$product = wc_get_product( $post->ID );
	$status = get_field('kich_hoat_chuc_nang_khac_vi', $post->ID);
	if( $status == 1 ):
		?>
			<div class="wrap_product_extra">
				<div class="check_option">
					<div class="option_lbl"><b><?php echo get_field('tieu_de_bang_gia_khac_ten','option'); ?></b></div>
					<span class="value">
						<input type="radio" name="check_product_extra" class="check_product_extra" value="0"> <span><?php echo __('Không','custom'); ?></span>
					</span>
					<span class="value">
						<input type="radio" name="check_product_extra" class="check_product_extra" value="1"> <span><?php echo __('Có','custom'); ?> ( <?php echo get_field('bang_gia_khac_ten_lbl','option'); ?> )</span>
					</span>
				</div>
				<div class="wrap_check_show_extra" style="display: none;">
					<div class="product_extra_field">
						<div class="field_lbl"><b><?php echo get_field('tieu_de_noi_dung_khac','option'); ?></b></div>
						<p class="description"><?php echo get_field('mo_ta_ngan_noi_dung_khac','option'); ?></p>
						<div class="field_input">
							<textarea name="engraved_content" id="engraved_content" placeholder="<?php echo get_field('ghi_chu_mo_ta_khac','option'); ?>"></textarea>
						</div>
					</div>
					<div class="product_extra_field">
						<div class="field_lbl"><b><?php echo get_field('tieu_de_kieu_chu','option'); ?></b></div>
						<p class="description"><?php echo get_field('mo_ta_ngan_kieu_chu','option'); ?></p>
						<div class="content_extra_field">
							<?php echo get_field('mo_ta_hinh_anh_kieu_chu','option'); ?>
						</div>
						<div class="wrap_select_style">
							<p><b><?php echo get_field('tieu_de_so_luong_kieu_chu','option'); ?></b></p>
							<div class="wrap_select">
								<select name="select_style" id="select_style">
									<option value="0"><?php echo __('Vui lòng chọn','custom'); ?></option>
									<?php $number_style = get_field('so_luong_kieu_chu','option'); ?>
									<?php for( $i = 1; $i <= $number_style ; $i++ ): ?>
										<option value="<?php echo $i; ?>">Style <?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="product_extra_field">
						<div class="field_lbl"><b><?php echo get_field('tieu_de_vi_tri_khac','option'); ?></b></div>
						<p class="description"><?php echo get_field('mo_ta_ngan_vi_tri_khac','option'); ?></p>
						<div class="content_extra_field">
							<?php echo get_field('mo_ta_hinh_anh_vi_tri_khac','option'); ?>
						</div>
						<div class="wrap_select_style">
							<p><b><?php echo get_field('tieu_de_chon_vi_tri','option'); ?></b></p>
							<div class="wrap_select">
								<select name="select_position" id="select_position">
									<option value="0"><?php echo __('Vui lòng chọn','custom'); ?></option>
									<?php $number_position = get_field('vi_tri_khac','option'); ?>
									<?php for( $i = 1; $i <= $number_position ; $i++ ): ?>
										<option value="<?php echo $i; ?>">Vị trí <?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="product_extra_field">
						<div class="field_lbl"><b><?php echo get_field('tieu_de_mo_ta_them','option'); ?></b></div>
						<p class="description"><?php echo get_field('mo_ta_ngan_mo_ta_them','option'); ?></p>
						<div class="field_input">
							<textarea name="more_content" id="more_content" placeholder="<?php echo get_field('ghi_chu_mo_ta_them','option'); ?>"></textarea>
						</div>
					</div>
					<div class="errors_extra" style="display: none;"></div>
				</div>
			</div>
		<?php
	endif;
}
add_action( 'woocommerce_before_add_to_cart_button', 'levano_display_field_product_extra' );

/**
 * Add the text field as item data to the cart object
 * @since 1.0.0
 * @param Array $cart_item_data Cart item meta data.
 * @param Integer $product_id Product ID.
 * @param Integer $variation_id Variation ID.
 * @param Boolean $quantity Quantity
 */
function levano_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
	if( $_POST['check_product_extra'] == 1 ) {
		// Add the item data
		$cart_item_data['engraved_content'] = $_POST['engraved_content'];
		$cart_item_data['select_style']     = $_POST['select_style'];
		$cart_item_data['select_position']  = $_POST['select_position'];
		$cart_item_data['more_content']     = $_POST['more_content'];
		$product = wc_get_product( $product_id ); // Expanded function
		$price = $product->get_price(); // Expanded function
		$cart_item_data['total_price'] = $price + get_field('bang_gia_khac_ten_value','option'); // Expanded function
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'levano_add_custom_field_item_data', 10, 4 );

/**
 * Update the price in the cart
 * @since 1.0.0
 */
function levano_before_calculate_totals( $cart_obj ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	// Iterate through each cart item
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		if( isset( $value['total_price'] ) ) {
			$price = $value['total_price'];
			$value['data']->set_price( ( $price ) );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'levano_before_calculate_totals', 10, 1 );

/**
 * Display the custom field value in the cart
 * @since 1.0.0
 */
function levano_cart_item_name( $name, $cart_item, $cart_item_key ) {
	if( isset( $cart_item['engraved_content'] ) ) {
		$name .= '<p>Nội dung khắc: '.$cart_item['engraved_content'].'<br> Chọn kiểu chữ bạn thích: Style '.$cart_item['select_style'].'<br> Chọn vị trí khắc: Ví trí '.$cart_item['select_position'].'<br> Mô tả thêm: '.$cart_item['more_content'].'</p>';
	}
	return $name;
}
add_filter( 'woocommerce_cart_item_name', 'levano_cart_item_name', 10, 3 );

/**
 * Add custom field to order object
 */
function levano_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	foreach( $item as $cart_item_key => $values ) {
		if( isset( $values['engraved_content'] ) ) {
			$item->add_meta_data( __( 'Nội dung khắc', 'custom' ), $values['engraved_content'], true );
		}
		if( isset( $values['select_style'] ) ) {
			$item->add_meta_data( __( 'Chọn kiểu bạn thích', 'custom' ), 'Style '.$values['select_style'], true );
		}
		if( isset( $values['select_position'] ) ) {
			$item->add_meta_data( __( 'Chọn vị trí', 'custom' ), 'Vị trí '.$values['select_position'], true );
		}
		if( isset( $values['more_content'] ) ) {
			$item->add_meta_data( __( 'Mô tả thêm', 'custom' ), $values['more_content'], true );
		}
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'levano_add_custom_data_to_order', 10, 4 );

// add script
add_action( 'wp_footer', 'levano_price_extra_script' );
function levano_price_extra_script()
{
	?>
		<script type="text/javascript" defer>
      		jQuery(function($) {

      			// check option
      			$('.wrap_product_extra .check_option input').on('change', function() {
			   		var check = $("input[name='check_product_extra']:checked").val();
			   		if( check == 1 ){
			   			$('.wrap_check_show_extra').show();

			   			// click button add to cart event
						$('.single_add_to_cart_button').click(function(e) {
							$('.errors_extra').html('');
							if( $('#engraved_content').val().length == 0 || $('#select_style').val() == 0 || $('#select_position').val() == 0 || $('#more_content').val().length == 0 ){							
								$('.errors_extra').show();
								if( $('#engraved_content').val().length == 0 ){
									$('.errors_extra').append('<p>Nội dung khắc không bỏ trống <br></p>');
								}
								if( $('#select_style').val() == 0 ){
									$('.errors_extra').append('<p>Chọn kiểu bạn thích không bỏ trống <br></p>');
								}
								if( $('#select_position').val() == 0 ){
									$('.errors_extra').append('<p>Chọn vị trí không bỏ trống <br></p>');
								}
								if( $('#more_content').val().length == 0 ){
									$('.errors_extra').append('<p>Mô tả thêm không bỏ trống <br></p>');
								}
								return false;
							}
							else{
								$('.errors_extra').hide();						
							}
						});
			   		}
			   		else{
			   			$('.wrap_check_show_extra').hide();
			   			$('.single_add_to_cart_button').attr('disabled', false);
			   		}
				});				
      		});
		</script>
	<?php
}