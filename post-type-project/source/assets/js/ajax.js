jQuery(function($) {

	if( $('#in_district').length > 0 ){
		load_quan();
		load_phuong_update();
		load_duong_update();
	}

    $('#city').on('change', function() {      
		load_quan();
	});
	function load_quan(){
		var data = {
			'action': 'load_district_ajax',
			'provinceid': $('#city').val()
		};
		if($('#city').val()==0){
			var html='<option value="0">Quận/huyện</option>';
			$('#district').html(html);
		}else{
			$.post(the_ajax_script.ajaxurl, data, function(rs){
				var html='<option value="0">Quận/huyện</option>';
				var id_curent = $('#city').attr('dis');
				$.each(rs, function(i, item) {
					if(rs[i].districtid==id_curent){
						html+='<option selected="selected" value="'+rs[i].districtid+'">'+rs[i].type+' '+rs[i].name+'</option>';
					}else{
						html+='<option value="'+rs[i].districtid+'">'+rs[i].type+' '+rs[i].name+'</option>';
					}
				})
				$('#district').html(html);
			});
		}
	}

	// show district
	$('#district').on('change', function() {	
		load_phuong_upload();
		load_duong_upload();	
	});
	function load_phuong_upload(){
		var data = {
			'action': 'load_ward_ajax',
			'districtid': $('#district').val()
		};
		if($('#district').val()==0){
			var html='<option value="0">Phường/xã</option>';
			$('#ward').html(html);
		}else{
			$.post(the_ajax_script.ajaxurl, data, function(rs){
	        	var html='<option value="0">Phường/xã</option>';
	        	var id_curent = $('#district').attr('war');
	        	$.each(rs, function(i, item) {
	        		if(rs[i].wardid==id_curent){
	        			html+='<option selected="selected" value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
	        		}else{
	        			html+='<option value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
	        		}
				})
				$('#ward').html(html);
	        });
        }
	}

	function load_phuong_update(){
		var data = {
			'action': 'load_ward_ajax',
			'districtid': $('#in_district').val()
		};
		$.post(the_ajax_script.ajaxurl, data, function(rs){
        	var html='';
        	var id_curent = $('#district').attr('war');
        	$.each(rs, function(i, item) {
        		if(rs[i].wardid==id_curent){
        			html+='<option selected="selected" value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
        		}else{
        			html+='<option value="'+rs[i].wardid+'">'+rs[i].type+' '+rs[i].name+'</option>';
        		}
			})
			$('#ward').html(html);
        });
	}

	function load_duong_upload(){
		var data = {
			'action': 'load_street_ajax',
			'districtid': $('#district').val()
		};
		if($('#district').val()==0){
			var html='<option value="0">Đường/phố</option>';
			$('#street').html(html);
		}else{
			$.post(the_ajax_script.ajaxurl, data, function(rs){
	        	var html='<option value="0">Đường/phố</option>';
	        	var id_curent = $('#district').attr('str');
	        	$.each(rs, function(i, item) {
	        		if(rs[i].streetid==id_curent){
	        			html+='<option selected="selected" value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
	        		}else{
	        			html+='<option value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
	        		}
				})
				$('#street').html(html);
	        });
        }
	}

	function load_duong_update(){
		var data = {
			'action': 'load_street_ajax',
			'districtid': $('#in_district').val()
		};
		$.post(the_ajax_script.ajaxurl, data, function(rs){
        	var html='';
        	var id_curent = $('#district').attr('str');
        	$.each(rs, function(i, item) {
        		if(rs[i].streetid==id_curent){
        			html+='<option selected="selected" value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
        		}else{
        			html+='<option value="'+rs[i].streetid+'">'+rs[i].type+' '+rs[i].name+'</option>';
        		}
			})
			$('#street').html(html);
        });
	}	


	// add project_title_new
	if( $('#project_title_new').length > 0 ){
		$('#project_title_new').on('change', function(e) {
			e.preventDefault();
			var title = $(this).val();
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
            return false;
		});
	}

	// add project_title_update
	if( $('#project_title_update').length > 0 ){
		$('#project_title_update').on('change', function(e) {
			e.preventDefault();
			var title = $(this).val();
			var project_id = $(this).data('projectid');
			$('.exits_project_title').remove();
			if( title != '' ){
				$.ajax({
	                type: 'POST',
	                url: the_ajax_script.ajaxurl,
	                data: ({
	                    action : 'check_project_title_update',
	                    title : title,
	                    project_id : project_id,
	                }),	
	                success: function(data) {
	                    if( data == 1 ){
	                    	$('<label class="error exits_project_title">Tiêu đề đã tồn tại.</label>').insertAfter('.input_title #project_title_update');
	                    	$('#frm_update_project button[type="submit"]').attr('disabled','disabled');
	                    }
	                    else {
	                    	$('#frm_update_project button[type="submit"]').removeAttr('disabled');
	                    }
	                },
	            });
			}			
            return false;
		});
	}


});