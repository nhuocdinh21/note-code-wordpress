<?php
/*
Template name: Page - Đặt lịch hẹn
*/
get_header(); ?>

<?php do_action( 'flatsome_before_page' ); ?>

<div id="content" role="main" class="content-area page-wrapper" style="background: url('<?php echo get_field('background'); ?>') 0 0 no-repeat;">
	<div class="row row-small layout_booking">
		<div class="col">
			<div class="col-inner text-center booking-title-t">
				<h3><?php echo get_the_title(); ?></h3>
				<h4>( CHỈ <span>2 BƯỚC</span> ĐƠN GIẢN )</h4>
				<p class="booking-title">.</p>
			</div>
		</div>
		<div class="col pb-0">
			<div class="col-inner">
				<div class="site-container">
					<div class="container">
						<div class="content-booking">
							<article class="entry">
								<div class="title khunggio"><span>1</span>ĐIỀN THÔNG TIN</div>
								<div class="content">
									<div class="input-group">
									    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									    <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Họ và Tên" style="z-index:1">
									</div>
									<div class="input-group">
									    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
									    <input type="text" class="form-control" name="customer_phone" id="customer_phone" placeholder="Số Điện Thoại" style="z-index:1">
									</div>
								</div>
							</article>
							<article class="entry">
								<div class="title"><span>2</span>CHỌN NGÀY VÀ GIỜ</div>
								<div class="content content-date">
									<div class="dv-title">
										<i class="fas fa-calendar"></i>
										NGÀY
									</div>
									<div class="dv-content">
										<input type="number" class="blue" name="date" id="date_booking" value="<?php echo date('d'); ?>" min="1" max="31" required>
										<input type="number" class="blue" name="month" id="month_booking" value="<?php echo date('m'); ?>" min="1" max="12" required>
										<input type="number" class="blue" name="year" id="year_booking" value="<?php echo date('Y'); ?>" required>
										<div class="clearfix"></div>
										<ul class="list-inline">
											<li>Ngày</li>
											<li>Tháng</li>
											<li>Năm</li>
										</ul>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="content content-time">
									<div class="dv-title">
										<i class="fas fa-clock"></i>
										KHUNG GIỜ
									</div>
									<div class="dv-content">
										<div class="time-item" data-value="8:30:00">8 : 30</div>
										<div class="time-item" data-value="9:00:00">9 : 00</div>
										<div class="time-item" data-value="9:30:00">9 : 30</div>
										<div class="time-item" data-value="10:00:00">10 : 00</div>
										<div class="time-item" data-value="10:30:00">10 : 30</div>
										<div class="time-item" data-value="11:00:00">11 : 00</div>
										<div class="time-item" data-value="11:30:00">11 : 30</div>
										<div class="time-item" data-value="12:00:00">12 : 00</div>
										<div class="time-item" data-value="12:30:00">12 : 30</div>
										<div class="time-item" data-value="13:00:00">13 : 00</div>
										<div class="time-item" data-value="13:30:00">13 : 30</div>
										<div class="time-item" data-value="14:00:00">14 : 00</div>
										<div class="time-item" data-value="14:30:00">14 : 30</div>
										<div class="time-item" data-value="15:00:00">15 : 00</div>
										<div class="time-item" data-value="15:30:00">15 : 30</div>
										<div class="time-item" data-value="16:00:00">16 : 00</div>
										<div class="time-item" data-value="16:30:00">16 : 30</div>
										<div class="time-item" data-value="17:00:00">17 : 00</div>
										<div class="time-item" data-value="17:30:00">17 : 30</div>
										<div class="time-item" data-value="18:00:00">18 : 00</div>
										<div class="time-item" data-value="18:30:00">18 : 30</div>
										<div class="time-item" data-value="19:00:00">19 : 00</div>
										<div class="time-item" data-value="19:30:00">19 : 30</div>
									</div>
								</div>
							</article>				
							<button class="btn-booking" id="btn-booking" type="submit">
								<article class="orange">
									<h3><?php echo __('Đặt lịch hẹn','custom'); ?></h3>
								</article>
							</button>
							<article class="entry entry-note">
								<div class="caution">*** XIN QUÝ KHÁCH LƯU Ý</div>
								<div class="note">
									<?php echo get_field('note'); ?>
								</div>								
							</article>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
   		var site_url = document.URL;
		$('.time-item').click(function(){
			$('.time-item').removeClass('time-item-active');
			$(this).addClass('time-item-active');
		});

	   	$('.address-item').click(function(){
			$('.address-item').removeClass('address-item-active');
	      	$(this).addClass('address-item-active');
	   	});
	 	$(document).on ( 'click', '#btn-booking', function(){   
			var phonenb = $('#customer_phone').val();
			var name = $('#customer_name').val();
			var khunggio = $('.time-item-active').data('value');
			var dates = $('#date_booking').val();
			var months = $('#month_booking').val();
			var years = $('#year_booking').val();
			var customer_name = $('input[name="customer_name"]').val();
			var customer_phone = $('input[name="customer_phone"]').val();
			var day = $('input[name="date"]').val();
			var month = $('input[name="month"]').val();
			var year = $('input[name="year"]').val();
			var date = year+'-'+month+'-'+day;
			var time = $('.time-item-active').data('value');
			var store_id = $('.address-item-active').data('value');
			var address = $('.address-item-active').data('name');
			if(customer_name == ''){
				$("html, body").delay(700).animate({
					scrollTop: $('div.content-booking').offset().top-70 
				}, 700);
				toastr["warning"]("Vui lòng nhập họ tên !");
				return false; 
			}
			if(customer_phone == ''){
		 		$("html, body").delay(700).animate({
			    	scrollTop: $('div.content-booking').offset().top-70 
			 	}, 700);
			 	toastr["warning"]("Vui lòng nhập số điện thoại !");
			 	return false; 
			}
			customer_phone = customer_phone.replace(/[^0-9]/g,'');
			if (customer_phone.length != 10)
			{
				toastr["warning"]("Vui lòng nhập đúng số điện thoại.");
			 	return false; 
			}
			if($('.time-item-active').length == 0){
				$("html, body").delay(700).animate({
					scrollTop: $('.khunggio').offset().top+110
				}, 700);
				toastr["warning"]("Vui lòng nhập thời gian !");
				return false; 
			}
			//AJAX
			$.ajax({
			    type: "POST",
			    datatype:"json",
			    url: '<?php echo admin_url('admin-ajax.php'); ?>',
			    data: {
			        action: 'send_ton',
			        phonecus: phonenb,
			        name_cus : name,
			        khunggios : khunggio,
			        dateb : dates,
			        monthb : months,
			        yearb : years
			    },
		     	beforeSend: function() {
				    $('#response').html("<img src='/assets/images/loading.gif' />");
			  	},
			    success: function(ketqua) { 
			        // Thử nghiệm
			        var ketqua = "Cảm ơn bạn đã gửi thông tin.! Chúng tôi sẽ liên hệ với bạn sớm nhất.";                
			        alert( ketqua );
			    }
			});
	    });
	});
</script>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
