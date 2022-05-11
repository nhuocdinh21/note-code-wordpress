<?php
/*
Template name: Page - Up Project
*/
if( !is_user_logged_in() ):
	wp_redirect( get_field('dang_nhap','option') ) ;
endif;

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$allowed_roles = array('contributor', 'author', 'editor', 'administrator');

	if( !array_intersect($allowed_roles, $current_user->roles ) ):
		wp_redirect( get_field('dang_nhap','option') ) ;		
	endif;

	get_header(); 	

	global $wpdb;
	$city = $wpdb->get_results( "SELECT * FROM province" );
?>

<?php do_action( 'flatsome_before_page' ); ?>

<div id="content" class="content-area page-wrapper" role="main">
	<div class="row row-main">
		<div class="large-12 col pb-0">
			<div class="col-inner">
				
				<?php if(get_theme_mod('default_title', 0)){ ?>
				<header class="entry-header">
					<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
				</header>
				<?php } ?>

				<div class="frm_up_project">
					<form method="post" id="frm_up_project" enctype="multipart/form-data">
						<div class="frm_inner">
							<div class="title_frm"><?php echo __('Đăng tin','custom'); ?></div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('Tôi muốn','custom'); ?>:</div>
									<div class="i_right">
										<div class="type_project">
											<span class="type_item">
							                    <input type="radio" name="project_type" value="thue" id="type_thue" checked class="input_type_project"> <label for="type_thue"><?php echo __('Cho thuê','custom'); ?></label>
							                </span>
							                <span class="type_item">
							                    <input type="radio" name="project_type" value="ban" id="type_ban" class="input_type_project"> <label for="type_ban"><?php echo __('Bán','custom'); ?></label>
							                </span>
										</div>
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left i_full"><?php echo __('Chọn loại hình','custom'); ?>:</div>
									<div class="i_right i_full">
										<div class="cat_project">
											<?php 
												$catechild = get_terms( 'danh-muc', array(
												    'orderby'    => 'menu_order',
												    'order'      =>'ASC',
												    'hide_empty' => 0,		    
												    'parent'     => 0,
												) );
											?>
											<?php if( $catechild ): ?>
												<?php $i = 1; foreach( $catechild as $cat ): ?>
													<div class="cat_item">
														<div class="inner">
															<input type="radio" name="project_cat" value="<?php echo $cat->term_id; ?>" id="cat_<?php echo $cat->term_id; ?>" class="input_cat_project" <?php if( $i == 1) echo 'checked'; ?>> <br>
															<label for="cat_<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></label>
														</div>
													</div>
												<?php $i++; endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('1. Tiêu đề','custom'); ?>:</div>
									<div class="i_right">
										<span class="input_title">
											<input type="text" name="project_title" id="project_title_new" placeholder="<?php echo __('Nhập tối đa 200 kí tự','custom'); ?>">
											<p class="note_input"><?php echo __('Ví dụ: Bán dãy nhà trọ, quận 10, TPHCM','custom'); ?></p>
										</span>											
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('2. Hình ảnh','custom'); ?>:</div>
									<div class="i_right">
										<div class="input_upload_wrap">
											<div class="input_item">
												<label for="file-upload" class="custom_upload_file">											
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/upload.png"> <?php echo __('Tải ảnh lên','custom'); ?>
												</label>	
												<input type="file" name="project_images[]" id="file-upload" multiple="multiple">
												<input type="text" class="cvf_hidden_field hidden" value="" disabled="disabled"> 
											</div>																					
											<ul class="preview_uploaded_files"></ul>
										</div>										
										<p class="note_input mt_5"><?php echo __('Lưu ý: Tối đa 3 ảnh. Ảnh thực tế sẽ giúp bạn tiếp cận được khách hàng hiệu quả.','custom'); ?></p>
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('3. Giá','custom'); ?>:</div>
									<div class="i_right">
										<span class="input_price_negotiate_wrap">
											<span class="input_price">
												<input type="number" name="project_price" id="project_price" min="1" placeholder="<?php echo __('Nhập số tiền','custom'); ?>"> <span class="unit">vnđ</span>
												<span class="preview_price"><span class="price"></span> vnđ</span>
											</span>
											<span class="input_price_negotiate">
												<input type="checkbox" name="project_price_negotiate" id="project_price_negotiate" class="input_price_negotiate"><label for="project_price_negotiate"><?php echo __('Thương lượng','custom'); ?></label>
											</span>
										</span>
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('4. Diện tích','custom'); ?>:</div>
									<div class="i_right">
										<span class="input_acreage_wrap">
											<span class="input_acreage">
												<input type="number" name="project_acreage" min="0" placeholder="<?php echo __('Nhập số','custom'); ?>">  <span class="unit">m²</span>
											</span>
										</span>
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('5. Địa chỉ','custom'); ?>:</div>
									<div class="i_right">
										<span class="input_address_wrap">
											<span class="input_address">
												<select name="city" id="city">
											  		<option value="0"><?php echo __('Tỉnh/thành','custom'); ?></option>
											  		<?php foreach ( $city as $item ) : ?>
											    		<option value="<?php echo $item->provinceid; ?>" <?php if( $item->provinceid == $_GET['city'] ) echo 'selected'; ?>><?php echo $item->name; ?></option>
											  		<?php endforeach; ?>
												</select>
											</span>
											<span class="input_address">										
												<select name="district" id="district">
											  		<option value="0"><?php echo __('Quận/huyện','custom'); ?></option>
												</select>
											</span>
											<span class="input_address">
												<select name="ward" id="ward">
											  		<option value="0"><?php echo __('Phường/xã','custom'); ?></option>
												</select>
											</span>
											<span class="input_address">
												<select name="street" id="street">
											  		<option value="0"><?php echo __('Đường/phố','custom'); ?></option>
												</select>
											</span>
											<span class="input_address">
												<input type="text" name="address" placeholder="<?php echo __('Nhập số nhà','custom'); ?>">
											</span>
										</span>											
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('6. Thông tin mô tả','custom'); ?>:</div>
									<div class="i_right">
										<span class="input_description">
											<textarea name="project_description" placeholder="<?php echo __('Nhập tối đa 5000  kí tự','custom'); ?>"></textarea>
										</span>										
									</div>
								</div>
							</div>
							<div class="frm_group">
								<div class="inner">
									<div class="i_left"><?php echo __('7. Liên hệ với tôi qua','custom'); ?>:</div>
									<div class="i_right">
										<div class="frm_infocontact">
											<div class="frm_item">
												<div class="item_lbl"><?php echo __('Tên liên lạc','custom'); ?>:</div>
												<div class="item_input">
													<input type="text" name="contact_name" placeholder="<?php echo __('Nhập tối đa 20 kí tự','custom'); ?>">
												</div>
											</div>
											<div class="frm_item">
												<div class="item_lbl"><?php echo __('Số điện thoại','custom'); ?>:</div>
												<div class="item_input">
													<span class="input_contact_phone_wrap">
														<span class="input_phone">
															<input type="tel" name="contact_phone" placeholder="<?php echo __('Nhập số điện thoại','custom'); ?>">
														</span>														
														<span class="agency">
															<input type="checkbox" name="agency" id="agency" value="1">	
															<label for="agency">Tick vào đây nếu bạn là Môi giới</label>
														</span>
													</span>													
												</div>
											</div>
											<div class="frm_item">
												<div class="item_lbl"><?php echo __('Khung giờ liên lạc tiện nhất','custom'); ?>:</div>
												<div class="item_input">
													<span class="group_check">
														<span class="group">
															<input type="radio" name="time_slot" id="bat_ky" value="1" checked>
															<label for="bat_ky">Bất kỳ</label>
														</span>
														<span class="group">
															<input type="radio" name="time_slot" id="sang" value="2">
															<label for="sang">Sáng</label>
														</span>
														<span class="group">
															<input type="radio" name="time_slot" id="trua" value="3">
															<label for="trua">Trưa</label>
														</span>
														<span class="group">
															<input type="radio" name="time_slot" id="chieu" value="4">
															<label for="chieu">Chiều</label>
														</span>
														<span class="group">
															<input type="radio" name="time_slot" id="toi" value="5">
															<label for="toi">Tối</label>
														</span>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="frm_group frm_submit">
								<div class="text-center">
									<button type="submit"><?php echo __('Đăng tin','custom'); ?></button>
									<div class="cancel_frm">
										<a href="<?php echo site_url(); ?>" onclick="history.go(-1); return false;"><?php echo __('Hủy','custom'); ?></a>
									</div>
									<input type="hidden" name="action" value="upload_new_project">
								</div>
							</div>
						</div>							
					</form>
				</div>

				<a href="#success_notification_upload" class="btn_success_notification_upload" class="hidden"></a>

				<div id="success_notification_upload" class="success_notification text-center">
					<div class="title"><?php echo get_field('tieu_de_thong_bao_dang_tin','option'); ?></div>
					<div class="notification">
						<p><?php echo get_field('thong_bao_1_dang_tin','option'); ?></p>
						<p><?php echo get_field('thong_bao_2_dang_tin','option'); ?></p>
						<p><?php echo get_field('thong_bao_3_dang_tin','option'); ?> <span id="result_project_id"></span></p>
					</div>
					<div class="noti_action">
						<a href="<?php echo get_field('quan_ly_tin_dang_lien_ket','option'); ?>">OK</a>
					</div>
				</div>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php do_action( 'flatsome_before_page_content' ); ?>
					
						<?php the_content(); ?>

						<?php if ( comments_open() || '0' != get_comments_number() ){
							comments_template(); } ?>

					<?php do_action( 'flatsome_after_page_content' ); ?>
				<?php endwhile; // end of the loop. ?>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>

<script>
	var limit = <?php echo get_field('so_luong_hinh_anh_co_the_upload','option'); ?>;
	jQuery(document).ready(function($){
	    $('#file-upload').change(function(){
	        var files = $(this)[0].files;
	        if(files.length > limit){
	            alert("Bạn chỉ có thể tải tối đa " + limit + " hình ảnh.");
	            $('#file-upload').val('');
	            return false;
	        }else{
	            return true;
	        }
	    });
	});
</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var storedFiles = [];

        // Apply sort function 
        function cvf_reload_order() {
            var order = $('.preview_uploaded_files').sortable('toArray', {attribute: 'item'});
            $('.cvf_hidden_field').val(order);
        }
       
        function cvf_add_order() {
            $('.preview_uploaded_files li').each(function(n) {
                $(this).attr('item', n);
            });
        }

        function count(array){
		    var c = 0;
		    for(i in array) // in returns key, not object
		        if(array[i] != undefined)
		            c++;

		    return c;
		}

        $(function() {
            $('.preview_uploaded_files').sortable({
                cursor: 'move',
                placeholder: 'highlight',
                start: function (event, ui) {
                    ui.item.toggleClass('highlight');
                },
                stop: function (event, ui) {
                    ui.item.toggleClass('highlight');
                },
                update: function () {
                    cvf_reload_order();
                },
                create:function(){
                    var list = this;
                    resize = function(){
                        $(list).css('height','auto');
                        $(list).height($(list).height());
                    };
                    $(list).height($(list).height());
                    $(list).find('img').load(resize).error(resize);
                }
            });
            $('.preview_uploaded_files').disableSelection();
        }); 

        $('body').on('change', '#file-upload', function() {
           
            var files = this.files;
            var i = 0;

            $('.preview_uploaded_files').html('');
                       
            for (i = 0; i < files.length; i++) {
                var readImg = new FileReader();
                var file = files[i];
               
                if (file.type.match('image.*')){
                    storedFiles.push(file);
                    readImg.onload = (function(file) {
                        return function(e) {
                            $('.preview_uploaded_files').append(
                            "<li file = '" + file.name + "'>" +                                
                                "<img class = 'img-thumb' src = '" + e.target.result + "' />" +
                                "<a href = '#' class = 'cvf_delete_image' title = 'Cancel'><img src='<?php echo get_stylesheet_directory_uri(); ?>/assets/images/time.png'></a>" +
                            "</li>"
                            );     
                        };
                    })(file);
                    readImg.readAsDataURL(file);
                   
                } else {
                    alert('File '+ file.name + ' không phải là hình ảnh.');
                }
               
                if(files.length === (i+1)){
                    setTimeout(function(){
                        cvf_add_order();
                    }, 1000);
                }
            }
        }); 

        // Delete Image from Queue
        $('body').on('click','a.cvf_delete_image',function(e){
            e.preventDefault();
            $(this).parent().remove('');       
           
            var file = $(this).parent().attr('file');            
            for(var i = 0; i < storedFiles.length; i++) {
                if(storedFiles[i].name == file) {
                    storedFiles.splice(i, 1);
                    if( storedFiles.length == 0 ){
                    	$('#file-upload').val('');
                    }
                    break;                    
                }
            }  
            cvf_reload_order();         
        });

        // add frm_up_project
	    if( $('#frm_up_project').length > 0 ){
	    	$('#frm_up_project').validate({
	      		rules: {
	        		'project_title': {
	        			required: true,
	        			maxlength: 200,
	    			},
		            'project_images[]': {
						required: true
		            },
		            'project_price': {
		            	required: true,
		            },
		            'project_acreage': {
		            	required: true,
		            },
		            'city' : {
		            	required: true,
		            	valueNotEquals: '0',
		            },
		            'district' : {
		            	required: true,
		            	valueNotEquals: '0',
		            },
		            'ward' : {
		            	required: true,
		            	valueNotEquals: '0',
		            },
		            'street' : {
		            	required: true,
		            	valueNotEquals: '0',
		            },
		            // 'address' : {
		            // 	required: true,
		            // },
		            'project_description' : {
		            	required: true,
	        			maxlength: 5000,
		            },
		            'contact_name' : {
		            	required: true,
		            	maxlength: 20,
		            },
		            'contact_phone' : {
		            	required: true,
		            	phoneUS: true
		            },
	      		},
	     		messages: {
	    			'project_title': {
	    				required: 'Bạn phải nhập Tiêu đề',
	    				maxlength: 'Nhập tối đa 200 kí tự',
	    			},
	        		'project_images[]': {
	          			required: 'Bạn phải tải lên tối thiểu 1 hình ảnh'
	        		},
	        		'project_price' : {
	        			required: 'Bạn phải nhập Số tiền',
	        			min: 'Vui lòng nhập giá trị lớn hơn hoặc bằng 1.',
	        		},
	        		'project_acreage' : {
	        			required: 'Bạn phải nhập Diện tích'
	        		},
	        		'city' : {
	        			required: 'Bạn phải chọn Tỉnh/thành phố',
	        			valueNotEquals: 'Bạn phải chọn',
	        		},
	        		'district' : {
	        			required: 'Bạn phải chọn Quận/huyện',
	        			valueNotEquals: 'Bạn phải chọn',
	        		},
	        		'ward' : {
	        			required: 'Bạn phải chọn Phường/xã',
	        			valueNotEquals: 'Bạn phải chọn',
	        		},
	        		'street' : {
	        			required: 'Bạn phải chọn Đường/phố',
	        			valueNotEquals: 'Bạn phải chọn',
	        		},
	        		// 'address' : {
	        		// 	required: 'Bạn phải nhập Số nhà'
	        		// },
	        		'project_description': {
	    				required: 'Bạn phải nhập Thông tin mô tả',
	    				maxlength: 'Nhập tối đa 5000 kí tự',
	    			},
	    			'contact_name' : {
	        			required: 'Bạn phải nhập Tên liên lạc',
	        			maxlength: 'Nhập tối đa 20 kí tự',
	        		},
	        		'contact_phone' : {
	        			required: 'Bạn phải nhập Số điện thoại',
	        			phoneUS: 'Số điện thoại không đúng',
	        		},
	      		},
	     		submitHandler: function (form) {
	     			var title = $('#project_title_new').val();
					$('.exits_project_title').remove();
					if( title != '' ){
						$.ajax({
			                type: 'POST',
			                url: the_ajax_script.ajaxurl,
			                data: ({
			                    action : 'check_project_title',
			                    title : title,
			                }),	
			                success: function(data) {
			                    if( data == 1 ){
			                    	$('<label class="error exits_project_title">Tiêu đề đã tồn tại.</label>').insertAfter('.input_title #project_title_new');
			                    	$('#frm_up_project button[type="submit"]').attr('disabled','disabled');
			                    }
			                    else {
			                    	$('#frm_up_project button[type="submit"]').removeAttr('disabled');
			                    }
			                },
			            });
					}

					var data = new FormData();

					//Form data
					var form_data = $('#frm_up_project').serializeArray();
					$.each(form_data, function (key, input) {
					    data.append(input.name, input.value);
					});

					//File data
					// var file_data = $('#file-upload')[0].files;
					// for (var i = 0; i < file_data.length; i++) {
					//     data.append("project_images[]", file_data[i]);
					// }

					cvf_reload_order();

					var items_array = $('.cvf_hidden_field').val();
	                var items = items_array.split(',');

	                for (var i in items){
	                    var item_number = items[i];
	                    data.append('project_images[]', storedFiles[item_number]);
	                }

					//Custom data
					data.append('key', 'value');

	 				$.ajax({     
	 					type: 'POST',
		                url: the_ajax_script.ajaxurl,        
			            data: data,
			            cache: false,             
			            processData: false, 
			            contentType: false,
			            beforeSend: function() {
					        $('#frm_up_project .frm_inner').append('<div class="loader"><div class="loading-spin"></div></div>');
					    },     
			            success: function(data_id) {	                
			                if( data_id != '' ){
			                	$('#frm_up_project .frm_inner .loader').hide();
			                	$('#success_notification_upload #result_project_id').text('#' + data_id);
			                }	
			                $('.btn_success_notification_upload').trigger('click');
			            }
			        });
			        return false; 
	     		}
	    	});
		}
    });
</script>
