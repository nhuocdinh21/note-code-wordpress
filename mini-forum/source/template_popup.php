<div class="cs_popup mfp-hide">
	<div class="background_overlay"></div>
	<div id="popup_login" class="popup_login">			
		<div class="content-popup">			
			<div class="top_popup">
				<h3><?php echo get_option('title_popup'); ?></h3>
			</div>
			<div class="content_request">
				<form class="frm_login_register">
				    <div class="form-group">
				        <label for="exampleInputEmail1">Email</label>
				        <input type="email" class="form-control" id="emailregister" name="your_email" placeholder="Email" required="required">
				    </div>
				    <div class="form-group">
				        <label for="exampleInputPassword1">Password</label>
				        <input type="password" class="form-control" id="passwordregister" name="your_password" placeholder="Password" required="required">
				    </div>	
		    		<div class="form-group">
				        <?php
							if( get_option('site_key') ):
								$site_key =  get_option('site_key');
							else:
								$site_key    = '6LeWOMsUAAAAAH58E0E-7X8Zq1Ft3sKQwVCHBwVT';
							endif;
						?>
						<script src='https://www.google.com/recaptcha/api.js'></script>
						<div class="g-recaptcha" data-sitekey="<?php echo $site_key?>" style="-webkit-transform-origin: 0 0;"></div>
				    </div>	    
				    <div class="btn_register">
				    	<button type="button" onclick="registerNewAccount()">Đăng ký / Đăng nhập</button>
				    </div>				    
				</form>
				<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
				<script type="text/javascript">
					jQuery(function($) {
					 	$(".frm_login_register").validate({
							rules: {
								your_email: {
									required: true,
									email: true
								},
								your_password: {
									required: true,
								},																
							},
							messages: {
								your_email: "<?php echo __('Vui lòng nhập một địa chỉ email hợp lệ.','custom'); ?>",
							}
						});
					});
				</script>				
			</div>
		</div>
	</div>
</div>