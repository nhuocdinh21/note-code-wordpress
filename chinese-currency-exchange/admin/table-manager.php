<?php
/**
 * Display a custom menu page
 */
if  ( ! function_exists( 'show_currency_exchange_setting' ) ){
	function show_currency_exchange_setting(){
		echo '<h1 class="currency_exchange_page_setting">Đổi tiền Trung Quốc</h1>';

		global $wpdb; 

		$table_name = $wpdb->prefix . 'alipay';

		$list_currency_exchange_sell = $wpdb->get_results("SELECT * FROM $table_name WHERE type LIKE 'sell' ORDER BY id DESC");
		$list_currency_exchange_buy  = $wpdb->get_results("SELECT * FROM $table_name WHERE type LIKE 'buy' ORDER BY id DESC");
		// var_dump($list_currency_exchange);

		?>
			<div class="addnew_currency_exchange_wrap">
				<div class="btn_addnew">
					<a href="javascript:;"><?php echo __('Thêm mới tỷ giá','phongmy'); ?></a>
				</div>
				<div class="addnew_currency_exchange">
					<div class="addnew_title text-center"><?php echo __('Thêm mới tỷ giá','phongmy'); ?></div>
					<form class="frm_addnew_currency_exchange">
						<div class="form_group">
							<div class="inner row large-columns-3 medium-columns-2 small-columns-1">
								<div class="item">
									<div class="i_label">
										<span><?php echo __('Phương thức','phongmy'); ?></span>
									</div>
									<div class="i_input">
										<select name="type">
											<option value="">-- <?php echo __('Chọn phương thức','phongmy'); ?> --</option>
											<option value="sell">Bán ra</option>
											<option value="buy">Mua vào</option>
										</select>
									</div>
								</div>								
								<div class="item">
									<div class="i_label">
										<span><?php echo __('Ngày','phongmy'); ?></span>
									</div>
									<div class="i_input">
										<input type="date" name="date">
									</div>
								</div>
								<div class="item">
									<div class="i_label">
										<span><?php echo __('Mốc thời gian','phongmy'); ?></span>
									</div>
									<div class="i_input">
										<select name="timeline">
											<option value="">-- <?php echo __('Chọn mốc thời gian','phongmy'); ?> --</option>
											<option value="10am">10AM</option>
											<option value="16pm">16PM</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form_group">
							<div class="inner row large-columns-3 medium-columns-2 small-columns-1">
								<div class="item">
									<div class="i_label">
										<span><?php echo __('Tỷ giá Alipay Trung','phongmy'); ?></span>
									</div>
									<div class="i_input">
										<input type="number" min="0" name="alipay_chinese">
									</div>
								</div>
								<div class="item">
									<div class="i_label">
										<span><?php echo __('Tỷ giá Alipay Việt','phongmy'); ?></span>
									</div>
									<div class="i_input">
										<input type="number" min="0" name="alipay_vietnamese">
									</div>
								</div>
								<div class="item">
									<div class="i_label">
										<span><?php echo __('Tỷ giá Tệ thẻ','phongmy'); ?></span>
									</div>
									<div class="i_input">
										<input type="number" min="0" name="card">
									</div>
								</div>								
							</div>
						</div>
						<div class="form_group">
							<div class="inner">
								<div class="item">
									<div class="i_input text-center">
										<button type="submit">Thêm mới</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="tab_currency_exchange_wrap">
				<ul id="tab_currency_exchange" class="nav_tabs">
					<li class="active"><a href="#sell_currency"><?php echo __('Bán ra','phongmy'); ?></a></li>
					<li><a href="#buy_currency"><?php echo __('Mua vào','phongmy'); ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="sell_currency">
						<table id="list_currency_exchange" class="list_currency_exchange" class="display nowrap" style="width:100%">
						  	<thead>
							    <tr>
									<th>Ngày</th>
									<th>Alipay Trung bán ra 10AM</th>
									<th>Alipay Trung bán ra 16PM</th>
									<th>Alipay Việt bán ra 10AM</th>
									<th>Alipay Việt bán ra 16PM</th>
									<th>Tệ thẻ bán ra 10AM</th>
									<th>Tệ thẻ bán ra 16PM</th>
									<th></th>
							    </tr>
						  	</thead>
						  	<tbody>
						  		<?php if( $list_currency_exchange_sell ): ?>
						  			<?php foreach( $list_currency_exchange_sell as $item ): ?>
						  				<?php 

						  					// set time
						  					date_default_timezone_set('Asia/Ho_Chi_Minh');

						  					$date_item = date('d/m/Y', strtotime($item->time));

						  					// set current_time
						  					$current_date = date('Y-d-m H:i:s');	

						  					// set day ago
						  					$day_ago = date( 'Y-m-d', strtotime( '-1 days', strtotime($item->time) ) );		  					

						  					// set currency value day ago
						  					$currency_exchange_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'sell' AND time LIKE '$day_ago%'");

						  					if( $currency_exchange_day_ago ){
						  						$currency_exchange_day_ago = $currency_exchange_day_ago;
						  					}
						  					else {
						  						// set 2 days ago
						  						$days_ago = date( 'Y-m-d', strtotime( '-2 days', strtotime($item->time) ) );
						  						$currency_exchange_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'sell' AND time LIKE '$days_ago%'");	
						  					}
						  					

						  					if (!empty($currency_exchange_day_ago)) {
						  						// set CHINESE EXCHANGE DAY AGO ---------------------------------------------------------------------------------------------------------------------------
						  						$chinese_10am_ago = $currency_exchange_day_ago->chinese_10am ? $currency_exchange_day_ago->chinese_10am : 0;
						  						$chinese_16pm_ago = $currency_exchange_day_ago->chinese_16pm ? $currency_exchange_day_ago->chinese_16pm : 0;

						  						// set VIETNAMESE EXCHANGE DAY AGO ---------------------------------------------------------------------------------------------------------------------------
						  						$vietnamese_10am_ago = $currency_exchange_day_ago->vietnamese_10am ? $currency_exchange_day_ago->vietnamese_10am : 0;
						  						$vietnamese_16pm_ago = $currency_exchange_day_ago->vietnamese_16pm ? $currency_exchange_day_ago->vietnamese_16pm : 0;

						  						// set CARD EXCHANGE DAY AGO ---------------------------------------------------------------------------------------------------------------------------
						  						$card_10am_ago = $currency_exchange_day_ago->card_10am ? $currency_exchange_day_ago->card_10am : 0;
						  						$card_16pm_ago = $currency_exchange_day_ago->card_16pm ? $currency_exchange_day_ago->card_16pm : 0;			  						
						  					}
						  					else {
						  						// chinese ---------------------------------------------------------------------------------------------------------------------------		  					
						  						$chinese_10am_ago = 0;
						  						$chinese_16pm_ago = 0;

						  						// vietnamese ---------------------------------------------------------------------------------------------------------------------------		  					
						  						$vietnamese_10am_ago = 0;
						  						$vietnamese_16pm_ago = 0;

						  						// card ---------------------------------------------------------------------------------------------------------------------------
						  						$card_10am_ago = 0;
						  						$card_16pm_ago = 0; 
						  					}


						  					// load Alipay Trung ---------------------------------------------------------------------------------------------------------------------------		  					
						  					$chinese_10am_today = $item->chinese_10am ? $item->chinese_10am : 0;
						  					$chinese_16pm_today = $item->chinese_16pm ? $item->chinese_16pm : 0;

						  					// load Alipay Viet ---------------------------------------------------------------------------------------------------------------------------		  					
						  					$vietnamese_10am_today = $item->vietnamese_10am ? $item->vietnamese_10am : 0;
						  					$vietnamese_16pm_today = $item->vietnamese_16pm ? $item->vietnamese_16pm : 0;

						  					// load Te the ---------------------------------------------------------------------------------------------------------------------------		  					
						  					$card_10am_today = $item->card_10am ? $item->card_10am : 0;
						  					$card_16pm_today = $item->card_16pm ? $item->card_16pm : 0;		  					

						  					// COMPARE CHINESE 2 DAY ---------------------------------------------------------------------------------------------------------------------------						  					
						  					$chinese_exchange_10am_status = ( $chinese_10am_today > $chinese_10am_ago ) ? 'up' : ( ( $chinese_10am_today < $chinese_10am_ago ) ? 'down' : '' );
						  					$chinese_exchange_16pm_status = ( $chinese_16pm_today > $chinese_16pm_ago ) ? 'up' : ( ( $chinese_16pm_today < $chinese_16pm_ago ) ? 'down' : '' );

						  					// COMPARE VIETNAMESE 2 DAY ---------------------------------------------------------------------------------------------------------------------------
						  					$vietnamese_exchange_10am_status = ( $vietnamese_10am_today > $vietnamese_10am_ago ) ? 'up' : ( ( $vietnamese_10am_today < $vietnamese_10am_ago ) ? 'down' : '' );
						  					$vietnamese_exchange_16pm_status = ( $vietnamese_16pm_today > $vietnamese_16pm_ago ) ? 'up' : ( ( $vietnamese_16pm_today < $vietnamese_16pm_ago ) ? 'down' : '' );

						  					// COMPARE CARD 2 DAY ---------------------------------------------------------------------------------------------------------------------------
						  					$card_exchange_10am_status = ( $card_10am_today > $card_10am_ago ) ? 'up' : ( ( $card_10am_today < $card_10am_ago ) ? 'down' : '' );
						  					$card_exchange_16pm_status = ( $card_16pm_today > $card_16pm_ago ) ? 'up' : ( ( $card_16pm_today < $card_16pm_ago ) ? 'down' : '' );

						  					
					  					?>
						  				<tr>
						  					<td><?php echo $date_item; ?></td>		  					
									      	<td>
									        	<a data-fancybox data-src="#update_sell_chinese_10am_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($chinese_10am_today); ?>đ</a> <?php if( $chinese_exchange_10am_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $chinese_exchange_10am_status; ?>.png" /><?php endif; ?>
									        	<div id="update_sell_chinese_10am_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="chinese_10am" data-price="<?php echo $chinese_10am_today; ?>" data-type="sell">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $chinese_10am_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_sell_chinese_16pm_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($chinese_16pm_today); ?>đ</a> <?php if( $chinese_exchange_16pm_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $chinese_exchange_16pm_status; ?>.png" /><?php endif; ?>
									        	<div id="update_sell_chinese_16pm_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="chinese_16pm" data-price="<?php echo $chinese_16pm_today; ?>" data-type="sell">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $chinese_16pm_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_sell_vietnamese_10am_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($vietnamese_10am_today); ?>đ</a> <?php if( $vietnamese_exchange_10am_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $vietnamese_exchange_10am_status; ?>.png" /><?php endif; ?>
									        	<div id="update_sell_vietnamese_10am_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="vietnamese_10am" data-price="<?php echo $vietnamese_10am_today; ?>" data-type="sell">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $vietnamese_10am_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_sell_vietnamese_16pm_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($vietnamese_16pm_today); ?>đ</a> <?php if( $vietnamese_exchange_16pm_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $vietnamese_exchange_16pm_status; ?>.png" /><?php endif; ?>
									        	<div id="update_sell_vietnamese_16pm_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="vietnamese_16pm" data-price="<?php echo $vietnamese_16pm_today; ?>" data-type="sell">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $vietnamese_16pm_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_sell_card_10am_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($card_10am_today); ?>đ</a> <?php if( $card_exchange_10am_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $card_exchange_10am_status; ?>.png" /><?php endif; ?>
									        	<div id="update_sell_card_10am_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="card_10am" data-price="<?php echo $card_10am_today; ?>" data-type="sell">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $card_10am_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_sell_card_16pm_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($card_16pm_today); ?>đ</a> <?php if( $card_exchange_16pm_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $card_exchange_16pm_status; ?>.png" /><?php endif; ?>
									        	<div id="update_sell_card_16pm_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="card_16pm" data-price="<?php echo $card_16pm_today; ?>" data-type="sell">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $card_16pm_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									      		<form class="frm_delete_data_row" data-id="<?php echo $item->id; ?>" data-type="sell">
									      			<button type="submit"><i class="dashicons dashicons-trash"></i></button>
									      		</form>
									      	</td>
						  				</tr>
					  				<?php endforeach; ?>
					  			<?php endif; ?>
						  	</tbody>
						</table>
					</div>
					<div class="tab-pane" id="buy_currency">
						<table id="list_currency_exchange" class="list_currency_exchange" class="display nowrap" style="width:100%">
						  	<thead>
							    <tr>
									<th>Ngày</th>
									<th>Alipay Trung mua vào 10AM</th>
									<th>Alipay Trung mua vào 16PM</th>
									<th>Alipay Việt mua vào 10AM</th>
									<th>Alipay Việt mua vào 16PM</th>
									<th>Tệ thẻ mua vào 10AM</th>
									<th>Tệ thẻ mua vào 16PM</th>
									<th></th>
							    </tr>
						  	</thead>
						  	<tbody>
						  		<?php if( $list_currency_exchange_buy ): ?>
						  			<?php foreach( $list_currency_exchange_buy as $item ): ?>
						  				<?php 

						  					// set time
						  					date_default_timezone_set('Asia/Ho_Chi_Minh');

						  					$date_item = date('d/m/Y', strtotime($item->time));

						  					// set current_time
						  					$current_date = date('Y-d-m H:i:s');	

						  					// set day ago
						  					$day_ago = date( 'Y-m-d', strtotime( '-1 days', strtotime($item->time) ) );		  					

						  					// set currency value day ago
						  					$currency_exchange_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'buy' AND time LIKE '$day_ago%'");

						  					if( $currency_exchange_day_ago ){
						  						$currency_exchange_day_ago = $currency_exchange_day_ago;
						  					}
						  					else {
						  						// set 2 days ago
						  						$days_ago = date( 'Y-m-d', strtotime( '-2 days', strtotime($item->time) ) );
						  						$currency_exchange_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'buy' AND time LIKE '$days_ago%'");	
						  					}
						  					

						  					if (!empty($currency_exchange_day_ago)) {
						  						// set CHINESE EXCHANGE DAY AGO ---------------------------------------------------------------------------------------------------------------------------
						  						$chinese_10am_ago = $currency_exchange_day_ago->chinese_10am ? $currency_exchange_day_ago->chinese_10am : 0;
						  						$chinese_16pm_ago = $currency_exchange_day_ago->chinese_16pm ? $currency_exchange_day_ago->chinese_16pm : 0;

						  						// set VIETNAMESE EXCHANGE DAY AGO ---------------------------------------------------------------------------------------------------------------------------
						  						$vietnamese_10am_ago = $currency_exchange_day_ago->vietnamese_10am ? $currency_exchange_day_ago->vietnamese_10am : 0;
						  						$vietnamese_16pm_ago = $currency_exchange_day_ago->vietnamese_16pm ? $currency_exchange_day_ago->vietnamese_16pm : 0;

						  						// set CARD EXCHANGE DAY AGO ---------------------------------------------------------------------------------------------------------------------------
						  						$card_10am_ago = $currency_exchange_day_ago->card_10am ? $currency_exchange_day_ago->card_10am : 0;
						  						$card_16pm_ago = $currency_exchange_day_ago->card_16pm ? $currency_exchange_day_ago->card_16pm : 0;			  						
						  					}
						  					else {
						  						// chinese ---------------------------------------------------------------------------------------------------------------------------		  					
						  						$chinese_10am_ago = 0;
						  						$chinese_16pm_ago = 0;

						  						// vietnamese ---------------------------------------------------------------------------------------------------------------------------		  					
						  						$vietnamese_10am_ago = 0;
						  						$vietnamese_16pm_ago = 0;

						  						// card ---------------------------------------------------------------------------------------------------------------------------
						  						$card_10am_ago = 0;
						  						$card_16pm_ago = 0; 
						  					}


						  					// load Alipay Trung ---------------------------------------------------------------------------------------------------------------------------		  					
						  					$chinese_10am_today = $item->chinese_10am ? $item->chinese_10am : 0;
						  					$chinese_16pm_today = $item->chinese_16pm ? $item->chinese_16pm : 0;

						  					// load Alipay Viet ---------------------------------------------------------------------------------------------------------------------------		  					
						  					$vietnamese_10am_today = $item->vietnamese_10am ? $item->vietnamese_10am : 0;
						  					$vietnamese_16pm_today = $item->vietnamese_16pm ? $item->vietnamese_16pm : 0;

						  					// load Te the ---------------------------------------------------------------------------------------------------------------------------		  					
						  					$card_10am_today = $item->card_10am ? $item->card_10am : 0;
						  					$card_16pm_today = $item->card_16pm ? $item->card_16pm : 0;		  					

						  					// COMPARE CHINESE 2 DAY ---------------------------------------------------------------------------------------------------------------------------						  					
						  					$chinese_exchange_10am_status = ( $chinese_10am_today > $chinese_10am_ago ) ? 'up' : ( ( $chinese_10am_today < $chinese_10am_ago ) ? 'down' : '' );
						  					$chinese_exchange_16pm_status = ( $chinese_16pm_today > $chinese_16pm_ago ) ? 'up' : ( ( $chinese_16pm_today < $chinese_16pm_ago ) ? 'down' : '' );

						  					// COMPARE VIETNAMESE 2 DAY ---------------------------------------------------------------------------------------------------------------------------
						  					$vietnamese_exchange_10am_status = ( $vietnamese_10am_today > $vietnamese_10am_ago ) ? 'up' : ( ( $vietnamese_10am_today < $vietnamese_10am_ago ) ? 'down' : '' );
						  					$vietnamese_exchange_16pm_status = ( $vietnamese_16pm_today > $vietnamese_16pm_ago ) ? 'up' : ( ( $vietnamese_16pm_today < $vietnamese_16pm_ago ) ? 'down' : '' );

						  					// COMPARE CARD 2 DAY ---------------------------------------------------------------------------------------------------------------------------
						  					$card_exchange_10am_status = ( $card_10am_today > $card_10am_ago ) ? 'up' : ( ( $card_10am_today < $card_10am_ago ) ? 'down' : '' );
						  					$card_exchange_16pm_status = ( $card_16pm_today > $card_16pm_ago ) ? 'up' : ( ( $card_16pm_today < $card_16pm_ago ) ? 'down' : '' );

						  					
					  					?>
						  				<tr>
						  					<td><?php echo $date_item; ?></td>		  					
									      	<td>
									        	<a data-fancybox data-src="#update_buy_chinese_10am_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($chinese_10am_today); ?>đ</a> <?php if( $chinese_exchange_10am_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $chinese_exchange_10am_status; ?>.png" /><?php endif; ?>
									        	<div id="update_buy_chinese_10am_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="chinese_10am" data-price="<?php echo $chinese_10am_today; ?>" data-type="buy">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $chinese_10am_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_buy_chinese_16pm_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($chinese_16pm_today); ?>đ</a> <?php if( $chinese_exchange_16pm_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $chinese_exchange_16pm_status; ?>.png" /><?php endif; ?>
									        	<div id="update_buy_chinese_16pm_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="chinese_16pm" data-price="<?php echo $chinese_16pm_today; ?>" data-type="buy">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $chinese_16pm_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_buy_vietnamese_10am_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($vietnamese_10am_today); ?>đ</a> <?php if( $vietnamese_exchange_10am_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $vietnamese_exchange_10am_status; ?>.png" /><?php endif; ?>
									        	<div id="update_buy_vietnamese_10am_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="vietnamese_10am" data-price="<?php echo $vietnamese_10am_today; ?>" data-type="buy">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $vietnamese_10am_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_buy_vietnamese_16pm_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($vietnamese_16pm_today); ?>đ</a> <?php if( $vietnamese_exchange_16pm_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $vietnamese_exchange_16pm_status; ?>.png" /><?php endif; ?>
									        	<div id="update_buy_vietnamese_16pm_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="vietnamese_16pm" data-price="<?php echo $vietnamese_16pm_today; ?>" data-type="buy">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $vietnamese_16pm_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_buy_card_10am_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($card_10am_today); ?>đ</a> <?php if( $card_exchange_10am_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $card_exchange_10am_status; ?>.png" /><?php endif; ?>
									        	<div id="update_buy_card_10am_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="card_10am" data-price="<?php echo $card_10am_today; ?>" data-type="buy">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $card_10am_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									        	<a data-fancybox data-src="#update_buy_card_16pm_<?php echo $item->id; ?>" href="javascript:;" class="update_data_table"><?php echo number_format($card_16pm_today); ?>đ</a> <?php if( $card_exchange_16pm_status != '' ): ?><img class="icon_exchange_status" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/exchange_<?php echo $card_exchange_16pm_status; ?>.png" /><?php endif; ?>
									        	<div id="update_buy_card_16pm_<?php echo $item->id; ?>" class="popup_update_data_table">
									        		<div class="title"></div>
									        		<form class="frm_update_data_table" data-id="<?php echo $item->id; ?>" data-fieldname="card_16pm" data-price="<?php echo $card_16pm_today; ?>" data-type="buy">
									        			<div class="form_group">
									        				<div class="form_group_input">
									        					<input type="number" name="field_value" value="<?php echo $card_16pm_today; ?>" min="0" required>
									        				</div>
									        				<div class="form_group_action">
									        					<input type="submit" value="<?php echo __('Cập nhật','phongmy'); ?>">
									        				</div>							        			
									        			</div>						        			
									        		</form>
									        	</div>
									      	</td>
									      	<td>
									      		<form class="frm_delete_data_row" data-id="<?php echo $item->id; ?>" data-type="buy">
									      			<button type="submit"><i class="dashicons dashicons-trash"></i></button>
									      		</form>
									      	</td>
						  				</tr>
					  				<?php endforeach; ?>
					  			<?php endif; ?>
						  	</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php
	}
}