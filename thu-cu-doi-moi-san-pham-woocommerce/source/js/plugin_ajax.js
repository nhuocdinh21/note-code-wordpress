jQuery(function($) {
	// click filter product by product cat
	if( $('.box-content__box-brand_1').length > 0 ){
		$('.box-content__box-brand_1 > input[name="optBrandold"]').click(function(e) {
			var cat = $(this).val();
			var data = {
				'action' : 'load_product_data',
				'cat'     : cat,
			};
			$.ajax({ // you can also use $.post here
				url : the_ajax_script.ajaxurl, // AJAX handler
				data : data,
				type : 'POST',
				beforeSend: function() {
			        $('.loader').removeClass('hidden');
			    },
				success : function( data ){	
					if( data ) { 
						$('.loader').addClass('hidden');
						$('.purchase_renew_products .wrap_box_list_product').empty();
						$('.purchase_renew_products .wrap_box_list_product').append(data);						
					}
				},
				error: (error) => {
					console.log(JSON.stringify(error));
				}
			});
		});

		$('.box-content__box-brand_1 > input:first-child').trigger('click');
	}

	// event click loadmore button
    $('.purchase_renew_products .wrap_box_list_product').on('click', '.btnShowMore', function() {
    	var page = $(this).attr('data-page'); // What page we are on.
		var ppp  = $(this).attr('data-offset'); // Post per page
		var cat  = $(this).attr('data-id'); // Cat
		var count = $(this).attr('data-count');
		var max_page = Math.ceil(count / ppp);

		var button = $(this),
		    data = {
				'action': 'load_more_product_data',
				'offset': page * ppp,
				'ppp'   : ppp,
				'cat'   : cat,
			};
 
		$.ajax({ // you can also use $.post here
			url  : the_ajax_script.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			success : function( data ){		
				if( data ) { 				
					if( page < max_page ){
						$('.box-content__list-product').append(data);
					}
					page++;	
					$('.btnShowMore').attr('data-page', page);
				}
				else
				{
					button.remove(); // if no data, remove the button as well
					$('.btnShowMore').remove();
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});
	});

	// add event search box
	if( $('#inpsearchold').length > 0 )
	{
		$("#inpsearchold").keyup(function(e) {
			if( $(this).val().length > 0 ){
				$(this).parents('.has-search').addClass('active');
				$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-times"></i>');
				$('.box-content__box-brand').hide();
				$('.wrap_box_list_product').empty();

				var ids = $(this).attr('data-id'); // What page we are on.
				var s = $(this).val();
				var data = {
					'action'  : 'load_product_data_search',
					'ids'     : ids,
					's'       : s,
				};
				$.ajax({ // you can also use $.post here
					url : the_ajax_script.ajaxurl, // AJAX handler
					data : data,
					type : 'POST',
					beforeSend: function() {
				        $('.loader').removeClass('hidden');
				    },
					success : function( data ){		
						if( data ) { 
							$('.loader').addClass('hidden');
							$('.wrap_box_list_product').empty();
							$('.wrap_box_list_product').append(data);
						}
						else{
							$('.wrap_box_list_product').append('<p class="text--none-product">Không tìm thấy sản phẩm, vui lòng nhập lại!</p>');
						}
					},
				});
			}
			else {
				$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-search"></i>');
				$('.box-content__box-brand').show();
				$('.box-content__box-brand > input:first-child').trigger('click');
			}
		});

		$('.has-feedback').on('click', '.fa-times', function() {
			$("#inpsearchold").val('');
			$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-search"></i>');
			$('.box-content__box-brand').show();
			$('.box-content__box-brand > input:first-child').trigger('click');
		});
	}

	// add class for html when click show modal
	$('.purchase_renew_products .wrap_box_list_product').on('click', 'li', function() {
		var modalThuCu = $('#modalThuCu');
		modalThuCu.modal();

		var id_product = $(this).attr('id');
		var title_product = $(this).attr('data-title');
		var img_product = $(this).attr('data-image');

		// excute load content modalThuCu
		var data_modalThuCu = {
			'action'  : 'load_data_modalThuCu',
			'id_product'     : id_product,
		};

		$.ajax({ // you can also use $.post here
			url : the_ajax_script.ajaxurl, // AJAX handler
			data : data_modalThuCu,
			type : 'POST',
			success : function( data ){		
				if( data ) { 
					$('#modalThuCu .modal-body').empty();
					$('#modalThuCu .modal-body').append(data);
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});

		$('#modalPay input[name="billing_note"]').val('[Thu Cũ] - Máy đang dùng: ' + title_product + ' (Loại 1)');	
		$('#modalPay .product_name').html('Tên sản phẩm: <strong>' + title_product + '</strong>');
		$('#modalPay .product_image').attr('src', img_product);	

		$('#modalPay input[name="product_id"]').val(id_product);		
		$('#modalPay input[name="product_title"]').val(title_product);	

		// add infor to modalPayNew
		$('#modalPayNew input[name="old_product_title"]').val(title_product + ' (Loại 1)');	
		
	});	

	// add event optMore
	var checked_val = 'null';
	$('#modalThuCu').on('click', 'input[name="optMore"]', function() {
		var price = $(this).val();
		var price_pri = $(this).attr('data-price');
		var price_extra = Number(price_pri) + Number(price);
				

		if($(this).val() == checked_val){			
		  	$('input[name=optMore][value=null]').prop('checked',true);
		    checked_val = 'null';

		    $('#modalThuCu .expected_price .box-right__price').text( number_format(price_pri, 0, ',', '.') + ' ₫' );
		$('#modalThuCu .expected_price .box-right__price').attr('data-price', price_pri);
	  	}else{	  		
		  	checked_val = $(this).val();

		  	$('#modalThuCu .expected_price .box-right__price').text( number_format(price_extra, 0, ',', '.') + ' ₫' );
			$('#modalThuCu .expected_price .box-right__price').attr('data-price', price_extra);
	  	}
	});

	


	// add event change type product
	$('#modalThuCu').on('click', 'input[name="optLoai"]', function() {
		$('#optMore1').prop( "checked", false );
		$('#optMore2').prop( "checked", false );

		var type = $(this).val();
		var price = $(this).attr('data-price');
		var pricenoformat = $(this).attr('data-pricenoformat');		
		$('#modalThuCu .expected_price u').text('loại ' + type);
		$('#modalThuCu .expected_price .box-right__price').attr('data-price', pricenoformat);
		$('#modalThuCu .expected_price .box-right__price').text(price);
		// show option more
		if( type == 1 ){
			$('#modalThuCu .box-right__box-option-more').show();
		}
		else{
			$('#modalThuCu .box-right__box-option-more').hide();
		}

		var title_product = $(this).attr('data-title');
		$('#modalPay input[name="billing_note"]').val('[Thu Cũ] - Máy đang dùng: ' + title_product + ' (Loại ' + type + ')');

		// add infor to modalPayNew
		$('#modalPayNew input[name="old_product_title"]').val( title_product + ' (Loại ' + type + ')' );	
	});
	
	// add event click modalAlert
	$('#modalAlert').on('click', '.btn_modalPay', function() {
		var modalPay = $('#modalPay');
		modalPay.modal();

		$('#modalPay .modal-header .modal-title').text('THÔNG TIN THU CŨ');
		var price = $('#modalThuCu .expected_price .box-right__price').attr('data-price');
		$('#modalPay .box-left__box-price .box-left__price').text( number_format(price, 0, ',', '.') + ' ₫' );
		$('#modalPay .box-left__box-price .box-left__price').attr( 'data-price', price );

		$('#modalPay input[name="billing_price"]').val(price);
	});

	// add validate form
	$('#frmThanhToan').validate({
		rules: {
			your_name: {
				required: true,
			},
			your_email: {
				required: true,
				email: true
			},
			your_phone: {
				required: true,
				phoneUS: true
			},	
			your_address: {
				required: true,
			}															
		},
		messages: {
			your_name: 'Vui lòng kiểm tra lại họ tên',
			your_email: 'Vui lòng kiểm tra lại email',
			your_phone: 'Vui lòng kiểm tra lại số điện thoại',
			your_address: 'Vui lòng kiểm tra lại địa chỉ',
		}
	});

	// add event click submit form frmThanhToan
	$('#frmThanhToan').on('click', 'button[type="submit"]', function(e) {

		// add validate form
		$('#frmThanhToan').valid();

		var your_name = $('#frmThanhToan input[name="your_name"]').val();
		var your_phone = $('#frmThanhToan input[name="your_phone"]').val();
		var your_email = $('#frmThanhToan input[name="your_email"]').val();
		var your_address = $('#frmThanhToan #your_address').val();
		var your_message = $('#frmThanhToan #your_message').val();
		var customer_note = $('#frmThanhToan input[name="billing_note"]').val();

		var product_id = $('#frmThanhToan input[name="product_id"]').val();
		var product_title = $('#frmThanhToan input[name="product_title"]').val();
		var billing_price = $('#frmThanhToan input[name="billing_price"]').val();

		// add validate form
		$('#frmThanhToan').valid();

		if( $('#frmThanhToan').valid() ){

			// excute load content modalThuCu
			var data = {
				'action'    : 'excute_save_information_product',
				'your_name' : your_name,
				'your_phone' : your_phone,
				'your_email' : your_email,
				'your_address' : your_address,
				'your_message' : your_message,
				'customer_note' : customer_note,
				'product_id' : product_id,
				'product_title' : product_title,
				'billing_price' : billing_price,
			};

			$.ajax({ // you can also use $.post here
				url : the_ajax_script.ajaxurl, // AJAX handler
				data : data,
				type : 'POST',
				beforeSend: function() {
			        $('.loader').removeClass('hidden');
			    },
				success : function( data ){		
					if( data ) {
						$('#frmThanhToan input[name="your_name"]').val('');
						$('#frmThanhToan input[name="your_phone"]').val('');
						$('#frmThanhToan input[name="your_email"]').val('');
						$('#frmThanhToan #your_message').val('');

						var action_url =  $('#frmThanhToan input[name="action"]').val() + '?ID=' + data;

						window.location.href = action_url;
					}
				},
			});
		}	

		e.preventDefault();
	});

	// add event click modalAlert
	$('#modalAlert').on('click', '.btn_modalListProduct', function() {
		var modalListProduct = $('#modalListProduct');
		modalListProduct.modal();

		var price = $('#modalThuCu .expected_price .box-right__price').attr('data-price');

		// excute load content modalListProduct
		var data = {
			'action'  : 'load_data_modalListProduct',
			'price'   : price,
		};

		$.ajax({ // you can also use $.post here
			url : the_ajax_script.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			success : function( data ){		
				if( data ) { 
					$('#modalListProduct .modal-body').empty();
					$('#modalListProduct .modal-body').append(data);

					$('#modalListProduct .box-content__box-brand_2 input[name="optBrandnew"]:first-child').trigger('click');
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});
	});

	// click filter product by product cat
	$('#modalListProduct').on('click', 'input[name="optBrandnew"]', function() {
		var cat = $(this).val();
		var price = $(this).attr('data-price');
		var data = {
			'action'  : 'load_product_newcat_data',
			'cat'     : cat,
			'price'   : price,
		};
		$.ajax({ // you can also use $.post here
			url : the_ajax_script.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend: function() {
		        $('.loader').removeClass('hidden');
		    },
			success : function( data ){		
				if( data ) { 
					$('.loader').addClass('hidden');
					$('#modalListProduct .wrap_box_list_product_2').empty();
					$('#modalListProduct .wrap_box_list_product_2').append(data);
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});
	});

	// click filter product by product cat
	$('#modalListProduct').on('click', 'input[name="optBrandold"]', function() {
		var cat = $(this).val();
		var price = $(this).attr('data-price');
		var data = {
			'action'  : 'load_product_oldcat_data',
			'cat'     : cat,
			'price'   : price,
		};
		$.ajax({ // you can also use $.post here
			url : the_ajax_script.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend: function() {
		        $('.loader').removeClass('hidden');
		    },
			success : function( data ){		
				if( data ) { 
					$('.loader').addClass('hidden');
					$('#modalListProduct .wrap_box_list_product_2').empty();
					$('#modalListProduct .wrap_box_list_product_2').append(data);
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});
	});

	// add event search box
	$('#modalListProduct').on('keyup', '#inpsearchnew', function() {
		if( $(this).val().length > 0 ){
			$(this).parents('.has-search').addClass('active');
			$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-times"></i>');
			$('.box-content__box-brand_2').hide();
			$('.wrap_box_list_product_2').empty();

			var ids = $(this).attr('data-id');
			var price = $(this).attr('data-price');
			var s = $(this).val();
			var data = {
				'action'  : 'load_product_newcat_data_search',
				'ids'     : ids,
				's'       : s,
				'price'   : price,
			};
			$.ajax({ // you can also use $.post here
				url : the_ajax_script.ajaxurl, // AJAX handler
				data : data,
				type : 'POST',
				beforeSend: function() {
			        $('.loader').removeClass('hidden');
			    },
				success : function( data ){		
					if( data ) { 
						$('.loader').addClass('hidden');
						$('.wrap_box_list_product_2').empty();
						$('.wrap_box_list_product_2').append(data);
					}
					else{
						$('.wrap_box_list_product_2').append('<p class="text--none-product">Không tìm thấy sản phẩm, vui lòng nhập lại!</p>');
					}
				},
				error: (error) => {
					console.log(JSON.stringify(error));
				}
			});
		}
		else {
			$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-search"></i>');
			$('.box-content__box-brand_2').show();
			$('#modalListProduct .box-content__box-brand_2 input[name="optBrandnew"]:first-child').trigger('click');
		}
	});

	$('#modalListProduct').on('click', '.fa-times', function() {
		$("#inpsearchnew").val('');
		$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-search"></i>');
		$('.box-content__box-brand_2').show();
		$('#modalListProduct .box-content__box-brand_2 input[name="optBrandnew"]:first-child').trigger('click');
	});

	// add event search box
	$('#modalListProduct').on('keyup', '#inpsearchold', function() {
		if( $(this).val().length > 0 ){
			$(this).parents('.has-search').addClass('active');
			$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-times"></i>');
			$('.box-content__box-brand_2').hide();
			$('.wrap_box_list_product_2').empty();

			var ids = $(this).attr('data-id');
			var price = $(this).attr('data-price');
			var s = $(this).val();
			var data = {
				'action'  : 'load_product_oldcat_data_search',
				'ids'     : ids,
				's'       : s,
				'price'   : price,
			};
			$.ajax({ // you can also use $.post here
				url : the_ajax_script.ajaxurl, // AJAX handler
				data : data,
				type : 'POST',
				success : function( data ){		
					if( data ) { 
						$('.wrap_box_list_product_2').empty();
						$('.wrap_box_list_product_2').append(data);
					}
					else{
						$('.wrap_box_list_product_2').append('<p class="text--none-product">Không tìm thấy sản phẩm, vui lòng nhập lại!</p>');
					}
				},
				error: (error) => {
					console.log(JSON.stringify(error));
				}
			});
		}
		else {
			$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-search"></i>');
			$('.box-content__box-brand_2').show();
			$('#modalListProduct .box-content__box-brand_2 input[name="optBrandold"]:nth-child(3)').trigger('click');
		}
	});

	// $('#modalListProduct').on('click', '.fa-times', function() {
	// 	$("#inpsearchold").val('');
	// 	$(this).parents('.has-search').find('.form-control-feedback').html('<i class="fa fa-search"></i>');
	// 	$('.box-content__box-brand_2').show();
	// 	$('#modalListProduct .box-content__box-brand_2 input[name="optBrandold"]:nth-child(3)').trigger('click');
	// });

	// click filter product by product cat
	$('#modalListProduct').on('click', '#may-cu', function() {
		var price = $(this).attr('data-price');

		// excute load content modalListProduct
		var data = {
			'action'  : 'load_data_oldcat_modalListProduct',
			'price'   : price,
		};

		$.ajax({ // you can also use $.post here
			url : the_ajax_script.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			success : function( data ){		
				if( data ) { 
					$('#modalListProduct .modal-body').empty();
					$('#modalListProduct .modal-body').append(data);

					$('#modalListProduct .box-content__box-brand_2 input[name="optBrandold"]:nth-child(3)').trigger('click');
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});
	});

	// click filter product by product cat
	$('#modalListProduct').on('click', '#may-moi', function() {
		var price = $(this).attr('data-price');

		// excute load content modalListProduct
		var data = {
			'action'  : 'load_data_newcat_modalListProduct',
			'price'   : price,
		};

		$.ajax({ // you can also use $.post here
			url : the_ajax_script.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			success : function( data ){		
				if( data ) { 
					$('#modalListProduct .modal-body').empty();
					$('#modalListProduct .modal-body').append(data);

					$('#modalListProduct .box-content__box-brand_2 input[name="optBrandnew"]:nth-child(1)').trigger('click');
				}
			},
			error: (error) => {
				console.log(JSON.stringify(error));
			}
		});
	});

	// add event click modalAlert
	$('#modalListProduct').on('click', 'li', function() {
		//hide modalPay
		var modalListProduct = $('#modalListProduct');
		modalListProduct.modal('hide');

		var modalPayNew = $('#modalPayNew');
		modalPayNew.modal();

		var title = $(this).attr('data-title');
		var id_product = $(this).attr('id');
		var image = $(this).attr('data-image');
		var price_new_phone = $(this).attr('data-price_new_phone');
		var price_tro_gia = $(this).attr('data-price_tro_gia');
		var price_old_phone = $(this).attr('data-price_old_phone');
		var price = $(this).attr('data-price');
		var old_product_title = $('#modalPayNew input[name="old_product_title"]').val();

		// gan gia tri cho form
		$('#modalPayNew input[name="product_id"]').val(id_product);
		$('#modalPayNew input[name="product_title"]').val(title);
		$('#modalPayNew input[name="price_new_phone"]').val(price_new_phone);
		$('#modalPayNew input[name="price_tro_gia"]').val(price_tro_gia);
		$('#modalPayNew input[name="price_old_phone"]').val(price_old_phone);
		$('#modalPayNew input[name="price"]').val(price);
		$('#modalPayNew input[name="billing_note"]').val('[Thu Cũ Đổi Mới] - Máy đang dùng: ' + old_product_title + ' - Máy lên đời: ' + title + '');

		$('#modalPayNew .modal-body .modal-body__box-left').empty();

		// add data
		$('#modalPayNew .modal-body .modal-body__box-left').append('<img class="product_name" src="' + image + '" >');
		$('#modalPayNew .modal-body .modal-body__box-left').append('<p class="product_name">Tên sản phẩm: <strong>' + title + '</strong></p>');

		$('#modalPayNew .modal-body .modal-body__box-left').append('<div class="box-left__box-price"><p>Giá máy mới:</p> <p class="box-left__price_new_phone">' + number_format( price_new_phone, 0, ',', '.') + ' ₫</p></div>');
		$('#modalPayNew .modal-body .modal-body__box-left').append('<div class="box-left__box-price"><p>Trợ giá:</p> <p class="box-left__price_new_phone">' + number_format( price_tro_gia, 0, ',', '.') + ' ₫</p></div>');
		$('#modalPayNew .modal-body .modal-body__box-left').append('<div class="box-left__box-price"><p>Giá máy cũ thu lại:</p> <p class="box-left__price_old_phone">' + number_format( price_old_phone, 0, ',', '.') + ' ₫</p></div>');
		$('#modalPayNew .modal-body .modal-body__box-left').append('<div class="box-left__box-price"><p>Giá bù chênh lệch:</p> <p class="box-left__price">' + number_format( price, 0, ',', '.') + ' ₫</p></div>');

	});

	// add validate form
	$('#frmThanhToanNew').validate({
		rules: {
			your_name: {
				required: true,
			},
			your_email: {
				required: true,
				email: true
			},
			your_phone: {
				required: true,
				phoneUS: true
			},	
			your_address_new : {
				required: true,
			}															
		},
		messages: {
			your_name: 'Vui lòng kiểm tra lại họ tên',
			your_email: 'Vui lòng kiểm tra lại email',
			your_phone: 'Vui lòng kiểm tra lại số điện thoại',
			your_address_new: 'Vui lòng kiểm tra lại địa chỉ',
		}
	});

	// add event click submit form frmThanhToan
	$('#frmThanhToanNew').on('click', 'button[type="submit"]', function(e) {

		$('#frmThanhToanNew').valid();
		
		var your_name = $('#frmThanhToanNew input[name="your_name"]').val();
		var your_phone = $('#frmThanhToanNew input[name="your_phone"]').val();
		var your_email = $('#frmThanhToanNew input[name="your_email"]').val();
		var your_address = $('#frmThanhToanNew #your_address_new').val();
		var your_message = $('#frmThanhToanNew #your_message_new').val();
		var customer_note = $('#frmThanhToanNew input[name="billing_note"]').val();

		var product_id = $('#frmThanhToanNew input[name="product_id"]').val();
		var product_title = $('#frmThanhToanNew input[name="product_title"]').val();
		var price_new_phone = $('#frmThanhToanNew input[name="price_new_phone"]').val();	
		var price_tro_gia = $('#frmThanhToanNew input[name="price_tro_gia"]').val();	
		var price_old_phone = $('#frmThanhToanNew input[name="price_old_phone"]').val();	
		var price = $('#frmThanhToanNew input[name="price"]').val();	

		// add validate form
		$('#frmThanhToanNew').valid();

		if( $('#frmThanhToanNew').valid() ){
			// excute load content modalThuCu
			var data = {
				'action'    : 'excute_save_information_product_new',
				'your_name' : your_name,
				'your_phone' : your_phone,
				'your_email' : your_email,
				'your_address' : your_address,
				'your_message' : your_message,
				'customer_note' : customer_note,
				'product_id' : product_id,
				'product_title' : product_title,
				'price_new_phone' : price_new_phone,
				'price_tro_gia' : price_tro_gia,
				'price_old_phone' : price_old_phone,
				'price' : price,
			};

			$.ajax({ // you can also use $.post here
				url : the_ajax_script.ajaxurl, // AJAX handler
				data : data,
				type : 'POST',
				beforeSend: function() {
			        $('.loader').removeClass('hidden');
			    },
				success : function( data ){		
					if( data ) {

						$('#frmThanhToanNew input[name="your_name"]').val('');
						$('#frmThanhToanNew input[name="your_phone"]').val('');
						$('#frmThanhToanNew input[name="your_email"]').val('');
						$('#frmThanhToanNew #your_message_new').val('');

						var action_url =  $('#frmThanhToanNew input[name="action"]').val() + '?ID=' + data;

						window.location.href = action_url;
					}
				},
			});
		}	

		e.preventDefault();
	});

	// click close modal
	$(document).click(function(event) {
		//if you click on anything except the modal itself or the "open modal" link, close the modal
		if ( !$(event.target).closest('.modal_enable, .purchase_renew_products').length ) {
			// hide modalThuCu
			var modalThuCu = $('#modalThuCu');			
			modalThuCu.modal('hide');
			$('#modalThuCu .modal-body').empty();
			// hide modalAlert
			var modalAlert = $('#modalAlert');
			modalAlert.modal('hide');

			//hide modalPay
			var modalPay = $('#modalPay');
			modalPay.modal('hide');

			//hide modalListProduct
			// var modalListProduct = $('#modalListProduct');
			// modalListProduct.modal('hide');

			//hide modalPayNew
			// var modalPayNew = $('#modalPayNew');
			// modalPayNew.modal('hide');
		}
	});
	$(document).click(function(event) {
        if($(event.target).closest('#modalListProduct .modal-content, #modalAlert .modal-content').length)
            return;

        // hide modalListProduct
		var modalListProduct = $('#modalListProduct');
		modalListProduct.modal('hide');
	});

	$(document).click(function(event) {
        if($(event.target).closest('#modalPayNew .modal-content, #modalListProduct .modal-content').length)
            return;

        // hide modalListProduct
		var modalPayNew = $('#modalPayNew');
		modalPayNew.modal('hide');
	});

	// product-left_blog-content_showmore
    if( $('.product-left_blog-content_showmore').length > 0 ){
    	$('.product-left_blog-content_showmore > a').click(function() {
	        $(this).parents('.box-quy-dinh__box-content').toggleClass('box_show');
	        // $(this).parents('.viewmore_features').hide();
	        $(this).html( $(this).html() == 'Xem thêm <i class="fa fa-angle-down"></i>' ? 'Thu gọn <i class="fa fa-angle-up"></i>' : 'Xem thêm <i class="fa fa-angle-down"></i>' );
	    });
    } 

});

// add function number_format
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 8 && 
    phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
}, "Please specify a valid phone number");

(function($) {
  $.fn.uncheckableRadio = function() {
    var $root = this;    
    $root.each(function() {
		var $radio = $(this);
		if ($radio.prop('checked')) {
			$radio.data('checked', true);
		} else {
			$radio.data('checked', false);
		}
        
      	$radio.click(function() {
	        var $this = $(this);
	        if ($this.data('checked')) {
				$this.prop('checked', false);
				$this.data('checked', false);
				$this.trigger('change');
	        } else {
				$this.data('checked', true);
				$this.closest('form').find('[name="' + $this.prop('name') + '"]').not($this).data('checked', false);
	        }
      	});
    });
    return $root;
  };
}(jQuery));