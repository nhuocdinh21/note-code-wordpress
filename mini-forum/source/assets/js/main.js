jQuery(function($) {
	// popup dang ky nhan uu dai
    setTimeout(function() {
		if ($('#popup_login').length) {
	   		$.magnificPopup.open({
	    		items: {
	        		src: '#popup_login' 
	    		},
	    		type: 'inline'
	      	});
	   }
	}, 1000);

	// show image cau hoi
	$("#inputthumb").change(function(evt) { 			  
		if (this.files && this.files[0]) {   
			var reader = new FileReader();
			var filename = $("#inputthumb").val();
			filename = filename.substring(filename.lastIndexOf('\\')+1);
			reader.onload = function(e) {
			  $('#q_thumb img').attr('src', e.target.result);
			  $('#q_thumb').hide();
			  $('#q_thumb').fadeIn(500);		              
			}
			reader.readAsDataURL(this.files[0]);    
		}
	});

});