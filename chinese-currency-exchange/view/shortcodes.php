<?php
if  ( ! function_exists( 'shortcode_chart_currency_exchange' ) ){
	// add shortcode [chart_currency_exchange]
	function shortcode_chart_currency_exchange() {
		ob_start();
			global $wpdb;
			$table_name = $wpdb->prefix . 'alipay';
			?>	
				<div class="chart_currency_exchange_wrap">
					<div class="inner">
						<?php if( get_option_chart('chart_currency_exchange_title') ): ?>
							<div class="title_box_chat">
								<<?php echo get_option_chart('chart_currency_exchange_tag_title') ? get_option_chart('chart_currency_exchange_tag_title') : 'h2'; ?> class="title"><?php echo get_option_chart('chart_currency_exchange_title'); ?></<?php echo get_option_chart('chart_currency_exchange_tag_title') ? get_option_chart('chart_currency_exchange_tag_title') : 'h2'; ?>>
							</div>
						<?php endif; ?>
						<div class="chart_wrapper tabs">
							<ul class="tab-links nav">
								<li class="tab active">
									<a data-href="#tab_chat_sell"><?php echo __('Bán ra','phongmy'); ?></a>
								</li>
								<li class="tab">
									<a data-href="#tab_chat_buy"><?php echo __('Mua vào','phongmy'); ?></a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_chat_sell">
									<div class="chart_container" style="min-height: <?php echo get_option_chart('chart_currency_exchange_chart_height') ? get_option_chart('chart_currency_exchange_chart_height') : '300'; ?>px;">
										<canvas id="speedChartSell"></canvas>
									</div>
								</div>
								<div class="tab-pane" id="tab_chat_buy">
									<div class="chart_container" style="min-height: <?php echo get_option_chart('chart_currency_exchange_chart_height') ? get_option_chart('chart_currency_exchange_chart_height') : '300'; ?>px;">
										<canvas id="speedChartBuy"></canvas>
									</div>
								</div>
							</div>
						</div>				
					</div>
				</div>
				<style type="text/css">
					:root {
						--chart_primary_color: <?php echo get_option_chart('chart_currency_exchange_primary_color') ? get_option_chart('chart_currency_exchange_primary_color') : '#6440fb'; ?>;
					}
				</style>
				<script type="text/javascript">
					// responsive
					function beforePrintHandler () {
					  for (let id in Chart.instances) {
					    Chart.instances[id].resize();
					  }
					} 
					// end responsive

					Chart.defaults.font.family = 'Roboto';
					Chart.defaults.font.size = 14;

					let chartOptions = {
				  		plugins: {
				            legend: {
				                position: 'bottom',
				            }			            
				        },
				        maintainAspectRatio: false,
			            responsive: true,
					};
				</script>
				<?php if( !empty(get_option_chart('chart_currency_exchange_sell_days')) ): ?>
					<?php 
						$str_list_sell_days = implode(',', get_option_chart('chart_currency_exchange_sell_days'));
						$list_sell_days_info = $wpdb->get_results(" SELECT * FROM $table_name WHERE id IN(".$str_list_sell_days.")");

						$labelsDataSell     = array();
						$dataChineseSell    = array();
						$dataVietnameseSell = array();
						$dataCardSell           = array();

						foreach( $list_sell_days_info as $item ):

							// get time
							$date_item = date('d/m', strtotime($item->time));		

							$labelChartItem10am = '"10AM ' . $date_item .'"';
							array_push($labelsDataSell, $labelChartItem10am);

							$labelChartItem16pm = '"16PM ' . $date_item . '"';
							array_push($labelsDataSell, $labelChartItem16pm);

							// chinese
							$chinese10am = $item->chinese_10am;
							array_push($dataChineseSell, $chinese10am);

							$chinese16pm = $item->chinese_16pm;
							if( $chinese16pm != 0 ){
								$chinese16pm = $chinese16pm;
							}
							else {
								$chinese16pm = $chinese10am;
							}
							array_push($dataChineseSell, $chinese16pm);

							// vietnamese
							$vietnamese10am = $item->vietnamese_10am;
							array_push($dataVietnameseSell, $vietnamese10am);

							$vietnamese16pm = $item->vietnamese_16pm;
							if( $vietnamese16pm != 0 ){
								$vietnamese16pm = $vietnamese16pm;
							}
							else {
								$vietnamese16pm = $vietnamese10am;
							}
							array_push($dataVietnameseSell, $vietnamese16pm);

							// card
							$card10am = $item->card_10am;
							array_push($dataCardSell, $card10am);

							$card16pm = $item->card_16pm;
							if( $card16pm != 0 ){
								$card16pm = $card16pm;
							}
							else {
								$card16pm = $card10am;
							}
							array_push($dataCardSell, $card16pm);
						endforeach;
						
						$str_labelsDataSell = implode(',', $labelsDataSell);

						$str_dataChineseDataSell    = implode(',', $dataChineseSell);
						$str_dataVietnameseDataSell = implode(',', $dataVietnameseSell);
						$str_dataCardDataSell    = implode(',', $dataCardSell);

					?>
					<script type="text/javascript">				
						let dataSellChinese = {
						    label: '<?php echo get_option_form('frm_exchange_title_currency_1') ? get_option_form('frm_exchange_title_currency_1') : __('Alipay Trung','phongmy'); ?>',
						    data: [<?php echo $str_dataChineseDataSell; ?>], // Giá trị của từng Line tương ứng với giá trị thơi gian AM, PM bên dưới
						    lineTension: 0.3, // độ mềm của line
						    fill: false,
						    borderColor: '<?php echo get_option_chart('chart_currency_exchange_alipay_chinese_border_color') ? get_option_chart('chart_currency_exchange_alipay_chinese_border_color') : 'purple'; ?>',
					  	};

						let dataSellVietnamese = {
						    label: '<?php echo get_option_form('frm_exchange_title_currency_2') ? get_option_form('frm_exchange_title_currency_2') : __('Alipay Việt','phongmy'); ?>',
						    data: [<?php echo $str_dataVietnameseDataSell; ?>],
						    lineTension: 0.3,
						    fill: false,
						    borderColor: '<?php echo get_option_chart('chart_currency_exchange_alipay_vietnamese_border_color') ? get_option_chart('chart_currency_exchange_alipay_vietnamese_border_color') : 'blue'; ?>',
					  	};

						let dataSellCard = {
						    label: '<?php echo get_option_form('frm_exchange_title_currency_3') ? get_option_form('frm_exchange_title_currency_3') : __('Tệ thẻ','phongmy'); ?>',
						    data: [<?php echo $str_dataCardDataSell; ?>],
						    lineTension: 0.3,
						    fill: false,
						  	borderColor: '<?php echo get_option_chart('chart_currency_exchange_alipay_card_border_color') ? get_option_chart('chart_currency_exchange_alipay_card_border_color') : 'green'; ?>',
					  	};

						let speedDataSell = {
						  	labels: [<?php echo $str_labelsDataSell; ?>],
						  	datasets: [dataSellChinese, dataSellVietnamese,dataSellCard]
						};

						let speedCanvasSell = document.getElementById("speedChartSell");

						let lineChartSell = new Chart(speedCanvasSell, {
					  		type: 'line',
						  	data: speedDataSell,
						  	options: chartOptions
						});
					</script>
				<?php endif; ?>
				<?php if( !empty(get_option_chart('chart_currency_exchange_buy_days')) ): ?>
					<?php 
						$str_list_buy_days = implode(',', get_option_chart('chart_currency_exchange_buy_days'));
						$list_buy_days_info = $wpdb->get_results(" SELECT * FROM $table_name WHERE id IN(".$str_list_buy_days.")");

						$labelsDataBuy     = array();
						$dataChineseBuy    = array();
						$dataVietnameseBuy = array();
						$dataCardBuy           = array();

						foreach( $list_buy_days_info as $item ):

							// get time
							$date_item = date('d/m', strtotime($item->time));		

							$labelChartItem10am = '"10AM ' . $date_item .'"';
							array_push($labelsDataBuy, $labelChartItem10am);

							$labelChartItem16pm = '"16PM ' . $date_item . '"';
							array_push($labelsDataBuy, $labelChartItem16pm);

							// chinese
							$chinese10am = $item->chinese_10am;
							array_push($dataChineseBuy, $chinese10am);

							$chinese16pm = $item->chinese_16pm;
							if( $chinese16pm != 0 ){
								$chinese16pm = $chinese16pm;
							}
							else {
								$chinese16pm = $chinese10am;
							}
							array_push($dataChineseBuy, $chinese16pm);

							// vietnamese
							$vietnamese10am = $item->vietnamese_10am;
							array_push($dataVietnameseBuy, $vietnamese10am);

							$vietnamese16pm = $item->vietnamese_16pm;
							if( $vietnamese16pm != 0 ){
								$vietnamese16pm = $vietnamese16pm;
							}
							else {
								$vietnamese16pm = $vietnamese10am;
							}
							array_push($dataVietnameseBuy, $vietnamese16pm);

							// card
							$card10am = $item->card_10am;
							array_push($dataCardBuy, $card10am);

							$card16pm = $item->card_16pm;
							if( $card16pm != 0 ){
								$card16pm = $card16pm;
							}
							else {
								$card16pm = $card10am;
							}
							array_push($dataCardBuy, $card16pm);
						endforeach;
						
						$str_labelsDataBuy = implode(',', $labelsDataBuy);

						$str_dataChineseDataBuy    = implode(',', $dataChineseBuy);
						$str_dataVietnameseDataBuy = implode(',', $dataVietnameseBuy);
						$str_dataCardDataBuy    = implode(',', $dataCardBuy);

					?>
					<script type="text/javascript">				
						let dataBuyChinese = {
						    label: '<?php echo get_option_form('frm_exchange_title_currency_1') ? get_option_form('frm_exchange_title_currency_1') : __('Alipay Trung','phongmy'); ?>',
						    data: [<?php echo $str_dataChineseDataBuy; ?>], // Giá trị của từng Line tương ứng với giá trị thơi gian AM, PM bên dưới
						    lineTension: 0.3, // độ mềm của line
						    fill: false,
						    borderColor: '<?php echo get_option_chart('chart_currency_exchange_alipay_chinese_border_color') ? get_option_chart('chart_currency_exchange_alipay_chinese_border_color') : 'purple'; ?>',
					  	};

						let dataBuyVietnamese = {
						    label: '<?php echo get_option_form('frm_exchange_title_currency_2') ? get_option_form('frm_exchange_title_currency_2') : __('Alipay Việt','phongmy'); ?>',
						    data: [<?php echo $str_dataVietnameseDataBuy; ?>],
						    lineTension: 0.3,
						    fill: false,
						    borderColor: '<?php echo get_option_chart('chart_currency_exchange_alipay_vietnamese_border_color') ? get_option_chart('chart_currency_exchange_alipay_vietnamese_border_color') : 'blue'; ?>',
					  	};

						let dataBuyCard = {
						    label: '<?php echo get_option_form('frm_exchange_title_currency_3') ? get_option_form('frm_exchange_title_currency_3') : __('Tệ thẻ','phongmy'); ?>',
						    data: [<?php echo $str_dataCardDataBuy; ?>],
						    lineTension: 0.3,
						    fill: false,
						  	borderColor: '<?php echo get_option_chart('chart_currency_exchange_alipay_card_border_color') ? get_option_chart('chart_currency_exchange_alipay_card_border_color') : 'green'; ?>',
					  	};

						let speedDataBuy = {
						  	labels: [<?php echo $str_labelsDataBuy; ?>],
						  	datasets: [dataBuyChinese, dataBuyVietnamese, dataBuyCard]
						};

						let speedCanvasBuy = document.getElementById("speedChartBuy");

						let lineChartBuy = new Chart(speedCanvasBuy, {
					  		type: 'line',
						  	data: speedDataBuy,
						  	options: chartOptions
						});
					</script>
				<?php endif; ?>
			<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	add_shortcode( 'chart_currency_exchange', 'shortcode_chart_currency_exchange' );
}

if  ( ! function_exists( 'shortcode_form_currency_exchange' ) ){
	// add shortcode [form_currency_exchange]
	function shortcode_form_currency_exchange() {
		ob_start();
			global $wpdb; 
			$table_name = $wpdb->prefix . 'alipay';
			// set time
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$current_date = date('Y-m-d');

			// set day ago
			$day_ago = date( 'Y-m-d', strtotime( '-1 days', strtotime($current_date) ) );

			// Excute Buy data
			// set Buy day ago
			$data_buy_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'buy' AND time LIKE '$day_ago%'");
			if( $data_buy_day_ago ){
				$data_buy_day_ago = $data_buy_day_ago;
			}
			else {
				// set 2 days ago
				$buy_days_ago = date( 'Y-m-d', strtotime( '-2 days', strtotime($current_date) ) );
				$data_buy_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'buy' AND time LIKE '$buy_days_ago%'");	
			}

			if( !empty($data_buy_day_ago) ){
				$chinese_buy_ago    = ( $data_buy_day_ago->chinese_16pm != 0 ) ? $data_buy_day_ago->chinese_16pm : ( ( $data_buy_day_ago->chinese_10am != 0 ) ? $data_buy_day_ago->chinese_10am : 0 );
				$vietnamese_buy_ago = ( $data_buy_day_ago->vietnamese_16pm != 0 ) ? $data_buy_day_ago->vietnamese_16pm : ( ( $data_buy_day_ago->vietnamese_10am != 0 ) ? $data_buy_day_ago->vietnamese_10am : 0 );
				$card_buy_ago       = ( $data_buy_day_ago->card_16pm != 0 ) ? $data_buy_day_ago->card_16pm : ( ( $data_buy_day_ago->card_10am != 0 ) ? $data_buy_day_ago->card_10am : 0 );
			}
			else {
				$chinese_buy_ago    = 0;
				$vietnamese_buy_ago = 0;
				$card_buy_ago       = 0;
			}

			// get data Buy today			
			$data_buy_today = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'buy' AND time LIKE '$current_date%'");
			if( !empty($data_buy_today) ){
				$chinese_buy_today    = ( $data_buy_today->chinese_16pm != 0 ) ? $data_buy_today->chinese_16pm : ( ( $data_buy_today->chinese_10am != 0 ) ? $data_buy_today->chinese_10am : $chinese_buy_ago );
				$vietnamese_buy_today = ( $data_buy_today->vietnamese_16pm != 0 ) ? $data_buy_today->vietnamese_16pm : ( ( $data_buy_today->vietnamese_10am != 0 ) ? $data_buy_today->vietnamese_10am : $vietnamese_buy_ago );
				$card_buy_today       = ( $data_buy_today->card_16pm != 0 ) ? $data_buy_today->card_16pm : ( ( $data_buy_today->card_10am != 0 ) ? $data_buy_today->card_10am : $card_buy_ago );
			}
			else {
				$chinese_buy_today    = $chinese_buy_ago;
				$vietnamese_buy_today = $vietnamese_buy_ago;
				$card_buy_today       = $card_buy_ago;
			}

			// Excute Sell data
			// set Sell day ago
			$data_sell_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'sell' AND time LIKE '$day_ago%'");
			if( $data_sell_day_ago ){
				$data_sell_day_ago = $data_sell_day_ago;
			}
			else {
				// set 2 days ago
				$sell_days_ago = date( 'Y-m-d', strtotime( '-2 days', strtotime($current_date) ) );
				$data_sell_day_ago = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'sell' AND time LIKE '$sell_days_ago%'");	
			}

			if( !empty($data_sell_day_ago) ){
				$chinese_sell_ago    = ( $data_sell_day_ago->chinese_16pm != 0 ) ? $data_sell_day_ago->chinese_16pm : ( ( $data_sell_day_ago->chinese_10am != 0 ) ? $data_sell_day_ago->chinese_10am : 0 );
				$vietnamese_sell_ago = ( $data_sell_day_ago->vietnamese_16pm != 0 ) ? $data_sell_day_ago->vietnamese_16pm : ( ( $data_sell_day_ago->vietnamese_10am != 0 ) ? $data_sell_day_ago->vietnamese_10am : 0 );
				$card_sell_ago       = ( $data_sell_day_ago->card_16pm != 0 ) ? $data_sell_day_ago->card_16pm : ( ( $data_sell_day_ago->card_10am != 0 ) ? $data_sell_day_ago->card_10am : 0 );
			}
			else {
				$chinese_sell_ago    = 0;
				$vietnamese_sell_ago = 0;
				$card_sell_ago       = 0;
			}

			// get data Sell today			
			$data_sell_today = $wpdb->get_row(" SELECT * FROM $table_name WHERE type LIKE 'sell' AND time LIKE '$current_date%'");
			if( !empty($data_sell_today) ){
				$chinese_sell_today    = ( $data_sell_today->chinese_16pm != 0 ) ? $data_sell_today->chinese_16pm : ( ( $data_sell_today->chinese_10am != 0 ) ? $data_sell_today->chinese_10am : $chinese_sell_ago );
				$vietnamese_sell_today = ( $data_sell_today->vietnamese_16pm != 0 ) ? $data_sell_today->vietnamese_16pm : ( ( $data_sell_today->vietnamese_10am != 0 ) ? $data_sell_today->vietnamese_10am : $vietnamese_sell_ago );
				$card_sell_today       = ( $data_sell_today->card_16pm != 0 ) ? $data_sell_today->card_16pm : ( ( $data_sell_today->card_10am != 0 ) ? $data_sell_today->card_10am : $card_sell_ago );
			}
			else {
				$chinese_sell_today    = $chinese_sell_ago;
				$vietnamese_sell_today = $vietnamese_sell_ago;
				$card_sell_today       = $card_sell_ago;
			}
			

			?>
				<div class="form_currency_exchange_wrapper">
					<div class="inner">
						<?php if( get_option_form('frm_exchange_title') ): ?>
							<div class="title_form_exchange">
								<<?php echo get_option_form('frm_exchange_tag_title') ? get_option_form('frm_exchange_tag_title') : 'h2'; ?> class="title"><?php echo get_option_form('frm_exchange_title'); ?></<?php echo get_option_form('frm_exchange_tag_title') ? get_option_form('frm_exchange_tag_title') : 'h2'; ?>>
							</div>
						<?php endif; ?>
						<div class="current_datetime">
							<div id="converter_clock"><?php echo date('G:i:s'); ?></div>
							<div id="converter_day"><?php echo date('d/m/Y'); ?></div>
						</div>
						<div class="data_currency_exchange">
							<div class="currency_row currency_title">
								<div class="item null"></div>
								<div class="item">
									<span style="background-color: <?php echo get_option_form('frm_exchange_currency_name_background') ? get_option_form('frm_exchange_currency_name_background') : '#E8FFF4'; ?>;"><?php echo get_option_form('frm_exchange_title_currency_2') ? get_option_form('frm_exchange_title_currency_2') : __('Alipay Việt','phongmy'); ?></span>
								</div>
								<div class="item">
									<span style="background-color: <?php echo get_option_form('frm_exchange_currency_name_background') ? get_option_form('frm_exchange_currency_name_background') : '#E8FFF4'; ?>;"><?php echo get_option_form('frm_exchange_title_currency_1') ? get_option_form('frm_exchange_title_currency_1') : __('Alipay Trung','phongmy'); ?></span>
								</div>								
								<div class="item">
									<span style="background-color: <?php echo get_option_form('frm_exchange_currency_name_background') ? get_option_form('frm_exchange_currency_name_background') : '#E8FFF4'; ?>;"><?php echo get_option_form('frm_exchange_title_currency_3') ? get_option_form('frm_exchange_title_currency_3') : __('Tệ thẻ','phongmy'); ?></span>
								</div>
							</div>
							<div class="currency_row currency_data">
								<div class="item title"><span><?php echo __('Mua vào','phongmy'); ?></span></div>
								<div class="item value" style="color: <?php echo get_option_form('frm_exchange_primary_color') ? get_option_form('frm_exchange_primary_color') : '#6440fb'; ?>;">
									<span id="vietnamese_buy"><?php echo number_format($vietnamese_buy_today); ?></span>
								</div>
								<div class="item value" style="color: <?php echo get_option_form('frm_exchange_primary_color') ? get_option_form('frm_exchange_primary_color') : '#6440fb'; ?>;">
									<span id="chinese_buy"><?php echo number_format($chinese_buy_today); ?></span>
								</div>								
								<div class="item value" style="color: <?php echo get_option_form('frm_exchange_primary_color') ? get_option_form('frm_exchange_primary_color') : '#6440fb'; ?>;">
									<span id="card_buy"><?php echo number_format($card_buy_today); ?></span>
								</div>
							</div>
							<div class="currency_row currency_data">
								<div class="item title"><span><?php echo __('Bán ra','phongmy'); ?></span></div>
								<div class="item value" style="color: <?php echo get_option_form('frm_exchange_secondary_color') ? get_option_form('frm_exchange_secondary_color') : '#EB5757'; ?>;">
									<span id="vietnamese_sell"><?php echo number_format($vietnamese_sell_today); ?></span>
								</div>
								<div class="item value" style="color: <?php echo get_option_form('frm_exchange_secondary_color') ? get_option_form('frm_exchange_secondary_color') : '#EB5757'; ?>;">
									<span id="chinese_sell"><?php echo number_format($chinese_sell_today); ?></span>
								</div>								
								<div class="item value" style="color: <?php echo get_option_form('frm_exchange_secondary_color') ? get_option_form('frm_exchange_secondary_color') : '#EB5757'; ?>;">
									<span id="card_sell"><?php echo number_format($card_sell_today); ?></span>
								</div>
							</div>
						</div>
						<div class="header__right__cal">
							<input type="hidden" name="data_chinese_buy" value="<?php echo $chinese_buy_today; ?>">
							<input type="hidden" name="data_vietnamese_buy" value="<?php echo $vietnamese_buy_today; ?>">
							<input type="hidden" name="data_card_buy" value="<?php echo $card_buy_today; ?>">
							<input type="hidden" name="data_chinese_sell" value="<?php echo $chinese_sell_today; ?>">
							<input type="hidden" name="data_vietnamese_sell" value="<?php echo $vietnamese_sell_today; ?>">
							<input type="hidden" name="data_card_sell" value="<?php echo $card_sell_today; ?>">
							<input type="hidden" name="service_fee" value="<?php echo get_option_form('frm_exchange_service_fee') ? get_option_form('frm_exchange_service_fee') : 0; ?>">
							<input type="hidden" name="payment_service_fee" value="<?php echo get_option_form('frm_exchange_payment_service_fee') ? get_option_form('frm_exchange_payment_service_fee') : 0; ?>">
							<div class="form_type_wrap">
								<div class="type_title"><?php echo __('Bạn muốn ?','phongmy'); ?></div>
								<div class="list_form_type">
									<div class="list_inner">
										<div class="overlay" style="background-color: <?php echo get_option_form('frm_exchange_primary_color') ? get_option_form('frm_exchange_primary_color') : '#6440fb'; ?>; opacity: 0.15;"></div>
										<div class="item">
											<input type="radio" name="exchange_type" id="type_buy" value="buy" checked>
											<label for="type_buy"><?php echo __('Mua tệ','phongmy'); ?></label>										
										</div>
										<div class="item">
											<input type="radio" name="exchange_type" id="type_sell" value="sell">
											<label for="type_sell"><?php echo __('Bán tệ','phongmy'); ?></label>										
										</div>
									</div>
								</div>
							</div>
							<div class="list_currency_exchange">
								<div class="list_inner">
									<div class="item">
										<input type="radio" name="currency_name" value="vietnamese" id="currency_vietnamese" checked>
										<label for="currency_vietnamese"><?php echo get_option_form('frm_exchange_title_currency_2') ? get_option_form('frm_exchange_title_currency_2') : __('Alipay Việt','phongmy'); ?></label>									
									</div>
									<div class="item">
										<input type="radio" name="currency_name" value="chinese" id="currency_chinese">
										<label for="currency_chinese"><?php echo get_option_form('frm_exchange_title_currency_1') ? get_option_form('frm_exchange_title_currency_1') : __('Alipay Trung','phongmy'); ?></label>									
									</div>								
									<div class="item">
										<input type="radio" name="currency_name" value="card" id="currency_card">
										<label for="currency_card"><?php echo get_option_form('frm_exchange_title_currency_3') ? get_option_form('frm_exchange_title_currency_3') : __('Tệ thẻ','phongmy'); ?></label>									
									</div>
								</div>
							</div>
							<div class="payment_service_fee_wrap">
								<div class="option_fee">
									<input type="checkbox" name="option_payment_service_fee" id="option_payment_service_fee">
									<label for="option_payment_service_fee"><?php echo __('Thanh toán hộ','phongmy'); ?></label>
								</div>
							</div>
							<div class="exchange_item vnd_change">
								<div class="form-group">
									<label>Số tiền (¥)</label>
									<input type="number" id="cny_input1" class="form-control" placeholder="¥">
								</div>
								<div class="button__change">
									<p>Đổi lại</p>
									<a href="javascript:;" style="background-color: <?php echo get_option_form('frm_exchange_third_color') ? get_option_form('frm_exchange_third_color') : '#0DFC89'; ?>;">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/change_icon.png" alt="" class="img-responsive">
									</a>
								</div>
								<div class="form-group">
									<label>Số tiền (VNĐ)</label>
									<input type="text" id="vnd_input1" class="form-control" disabled="true" placeholder="VNĐ">
								</div>
							</div>
							<div class="exchange_item cny_change">
								<div class="form-group">
									<label>Số tiền (VNĐ)</label>
									<input type="number" id="vnd_input2" class="form-control" placeholder="VNĐ">
								</div>
								<div class="button__change">
									<p>Đổi lại</p>
									<a href="javascript:;" style="background-color: <?php echo get_option_form('frm_exchange_third_color') ? get_option_form('frm_exchange_third_color') : '#0DFC89'; ?>;">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/change_icon.png" alt="" class="img-responsive">
									</a>
								</div>
								<div class="form-group">
									<label>Số tiền (¥)</label>
									<input type="text" id="cny_input2" class="form-control" disabled="true" placeholder="¥">
								</div>
							</div>
							<div class="change__detail-info">
								<ul>
									<li><span><?php echo __('Tỷ giá sử dụng','phongmy'); ?>:</span> <p id="money_exchange">0</p></li>
									<li><span><?php echo __('Phí dịch vụ','phongmy'); ?>:</span> <p><?php echo get_option_form('frm_exchange_service_fee') ? number_format(get_option_form('frm_exchange_service_fee')) : 0; ?> <sup>đ</sup></p></li>
									<li><span><?php echo __('Thanh toán hộ','phongmy'); ?>:</span> <p><b id="payment_change">0 <sup>đ</sup></b></p></li>
									<li><span><?php echo __('Số tiền thanh toán','phongmy'); ?>:</span> <p id="number_receive">0 <sup>đ</sup></p></li>
								</ul>
							</div>
						</div>							
						<?php if( get_option_form('frm_exchange_description') ): ?>
							<div class="form_exchange_description" style="background-color: <?php echo get_option_form('frm_exchange_description_background') ? get_option_form('frm_exchange_description_background') : '#fdeeee'; ?>;">
								<?php echo wpautop( get_option_form('frm_exchange_description') ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<style type="text/css">
					:root {
						--form_primary_color: <?php echo get_option_chart('frm_exchange_primary_color') ? get_option_chart('frm_exchange_primary_color') : '#6440fb'; ?>;
						--form_secondary_color: <?php echo get_option_chart('frm_exchange_secondary_color') ? get_option_chart('frm_exchange_secondary_color') : '#EB5757'; ?>;
						--form_third_color: <?php echo get_option_chart('frm_exchange_third_color') ? get_option_chart('frm_exchange_third_color') : '#0DFC89'; ?>;
					}
				</style>
			<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	add_shortcode( 'form_currency_exchange', 'shortcode_form_currency_exchange' );
}