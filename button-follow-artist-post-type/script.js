if( $('.artist_info .follow_button_login').length > 0 ){
		var follow_status = $('.artist_info .follow_button_login').data('follow');

		if( follow_status == 1 ){
			$('.artist_info .follow_button_login .icon_follow').html('<i class="fas fa-check"></i>');
			$('.artist_info .follow_button_login .following_text').show();
			$('.artist_info .follow_button_login .not_following_text').hide();
		}
		$('body').on('click', '.artist_info .follow_button_login', function() {
			var user_id = $(this).data('id');
			var postid = $(this).data('postid');
			var username = $(this).data('username');
			$.ajax({
	            type: 'POST',
	            url: the_ajax_script.ajaxurl,
	            data: ({
	                action : 'update_follow_artist',
	                user_id : user_id,
	                postid : postid,
	                username : username,
	            }),	
	            success: function(data) {
	                if( data.status == 1 ){
	                	$('.artist_info .follow_button_login').attr('data-follow', 1);
	                	$('.artist_info .follow_button_login .icon_follow').html('<i class="fas fa-check"></i>');
	                	$('.artist_info .follow_button_login .following_text').show();
						$('.artist_info .follow_button_login .not_following_text').hide();

						$('.artist_imgtool .follow_button_login').attr('data-follow', 1);
	                	$('.artist_imgtool .follow_button_login .icon_follow').html('<i class="fas fa-check"></i>');
	                	$('.artist_imgtool .follow_button_login .following_text').show();
						$('.artist_imgtool .follow_button_login .not_following_text').hide();
	                }
	                else{
	                	$('.artist_info .follow_button_login').attr('data-follow', 0);
	                	$('.artist_info .follow_button_login .icon_follow').html('<i class="fas fa-plus"></i>');
	                	$('.artist_info .follow_button_login .following_text').hide();
						$('.artist_info .follow_button_login .not_following_text').show();

						$('.artist_imgtool .follow_button_login').attr('data-follow', 0);
	                	$('.artist_imgtool .follow_button_login .icon_follow').html('<i class="fas fa-plus"></i>');
	                	$('.artist_imgtool .follow_button_login .following_text').hide();
						$('.artist_imgtool .follow_button_login .not_following_text').show();
	                }
	                if( data.count ){
	                	$('.artist_imgtool .artist_follow span').text(data.count);
	                }
	            },
	        });
		});			
	}

	if( $('.artist_imgtool .follow_button_login').length > 0 ){
		var follow_status = $('.artist_imgtool .follow_button_login').data('follow');

		if( follow_status == 1 ){
			$('.artist_imgtool .follow_button_login .icon_follow').html('<i class="fas fa-check"></i>');
			$('.artist_imgtool .follow_button_login .following_text').show();
			$('.artist_imgtool .follow_button_login .not_following_text').hide();
		}
		$('body').on('click', '.artist_imgtool .follow_button_login', function() {
			var user_id = $(this).data('id');
			var postid = $(this).data('postid');
			var username = $(this).data('username');
			$.ajax({
	            type: 'POST',
	            url: the_ajax_script.ajaxurl,
	            data: ({
	                action : 'update_follow_artist',
	                user_id : user_id,
	                postid : postid,
	                username : username,
	            }),	
	            success: function(data) {
	            	console.log(data);
	                if( data.status == 1 ){
	                	$('.artist_imgtool .follow_button_login').attr('data-follow', 1);
	                	$('.artist_imgtool .follow_button_login .icon_follow').html('<i class="fas fa-check"></i>');
	                	$('.artist_imgtool .follow_button_login .following_text').show();
						$('.artist_imgtool .follow_button_login .not_following_text').hide();

						$('.artist_info .follow_button_login').attr('data-follow', 1);
	                	$('.artist_info .follow_button_login .icon_follow').html('<i class="fas fa-check"></i>');
	                	$('.artist_info .follow_button_login .following_text').show();
						$('.artist_info .follow_button_login .not_following_text').hide();
	                }
	                else{
	                	$('.artist_imgtool .follow_button_login').attr('data-follow', 0);
	                	$('.artist_imgtool .follow_button_login .icon_follow').html('<i class="fas fa-plus"></i>');
	                	$('.artist_imgtool .follow_button_login .following_text').hide();
						$('.artist_imgtool .follow_button_login .not_following_text').show();

						$('.artist_info .follow_button_login').attr('data-follow', 0);
	                	$('.artist_info .follow_button_login .icon_follow').html('<i class="fas fa-plus"></i>');
	                	$('.artist_info .follow_button_login .following_text').hide();
						$('.artist_info .follow_button_login .not_following_text').show();
	                }
	                if( data.count ){
	                	$('.artist_imgtool .artist_follow span').text(data.count);
	                }
	            },
	        });
		});			
	}