jQuery(function($) {
	// add event load slide
	$(window).bind("load resize scroll",function(e){
  		$('.slider').flickity('resize');
	});

	// remove tag p empty content
	$('p').each(function() {
		var $this = jQuery(this);
		if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
			$this.remove(); 
	});

	if( $('#wpadminbar').length > 0 ){
		var top_bar = 32;
	}
	else {
		var top_bar = 0;
	}

	// add banner_showcase
	if( $('.banner_showcase').length > 0 ){
		$('.banner_showcase').owlCarousel({
		    loop: true,
	        nav: false,
	        dots: true,
	        margin: 1,
	        autoplay: true,
	        autoplayTimeout: 3000,
	        martSpeed: 350,
	        items: 1,
	        autoplayHoverPause: true,
	    });
	}

	// add banner_showcase_mobile
	if( $('.banner_showcase_mobile').length > 0 ){
		$('.banner_showcase_mobile').owlCarousel({
		    loop: true,
	        nav: false,
	        dots: true,
	        margin: 1,
	        autoplay: true,
	        autoplayTimeout: 3000,
	        martSpeed: 350,
	        items: 1,
	        autoplayHoverPause: true,
	    });
	}

	// add slide_image_project
	if( $('.slide_image_project').length > 0 ){
		$('.slide_image_project').owlCarousel({
		    loop: true,
	        nav: true,
	        dots: true,
	        margin: 1,
	        autoplay: true,
	        autoplayTimeout: 4000,
	        martSpeed: 350,
	        items: 1,
	        autoplayHoverPause: true,
	    });
	}

	// add phoneEvent
	if( $('.phoneEvent').length > 0 ){
		$('.phoneEvent').click(function () {
	        var n = $(this).attr('raw');
	        if ($(this).hasClass("showHotline") == !1) {
	            u();
	            copyToClipboard(n);
	            showToolTip($('.phoneEvent'));
	        } else {
	            copyToClipboard(n);
	            showToolTip($(this));
	            return !1;
	        }
	    });
    }

    // add btn_copylink
    if( $('.btn_copylink').length > 0 ){
		$('.btn_copylink').click(function () {
	        var n = $(this).attr('raw');
	        copyToClipboard(n);
            showToolTip($('.copy_link_project'));
	    });
    }

    // add frm_update_account
    if( $('#frm_update_account').length > 0 ){
    	$('#frm_update_account').validate({
      		rules: {
        		user_name: 'required',
	            user_phone: {
					required: true,
					phoneUS: true
	            },
      		},
     		 messages: {
    			user_name: 'Bạn phải nhập Họ tên',
        		user_phone: {
          			required: 'Bạn phải nhập Số điện thoại',
          			phoneUS: 'Số điện thoại không đúng',
        		},
      		}
    	});
	}

	// add project_price_negotiate
	if( $('#project_price_negotiate').length > 0 ){
		$('#project_price_negotiate').click(function () {
	        if( $('#project_price_negotiate').prop('checked') ){
	        	$('#project_price').attr('disabled','disabled');
	        	$('#project_price').val('');
	        	$('.input_price .preview_price').hide();
	        }
	        else{
	        	$('#project_price').removeAttr('disabled');
	        }
	    });
    }

    // add project_price
    if( $('#project_price').length > 0 ){    
    	if( $('#project_price').val() == '' ){
    		$('.input_price .preview_price').hide();
    	}	
    	$('#project_price').keyup(function() {    		
	    	var price = formatNumber($(this).val(), '.', ',');
	    	$('.input_price .preview_price').show();
	        $('.input_price .preview_price .price').text(price);

	        if( $('#project_price').val() == '' ){
	    		$('.input_price .preview_price').hide();
	    	}
        });
    }

	$('.btn_success_notification_upload, .btn_success_notification_update').fancybox({
		modal: true,
		hideOnContentClick : false,
		closeClick  : false,
		openEffect  : 'none',
		closeEffect : 'none',
		showCloseButton : false,
		hideOnContentClick: false,
        closeBtn : false,
		clickSlide : 'false',
		clickOutside : 'false',
		touch: false,
		helpers     : { 
			overlay : {
				closeClick: false
			} // prevents closing when clicking OUTSIDE fancybox 
		}
	});

	// add next_nav
	$('.next_nav').click(function (e) { 
		e.preventDefault();
		$('.mobile_menu').animate({
			scrollLeft: "+=50px"
		}, "slow");
	});

	// add left banner postion
	$(window).bind("load resize scroll",function(e){
		var top_banner = $('#main').offset().top;
		var scroll = $(window).scrollTop();
        // set position arrow
        if( $('.banner_left').length > 0 ){
        	var element_wrap = $('body');
        	var offset_wrap = element_wrap.offset();
        	
        	$('.banner_left').css('width', offset_wrap.left);

        	if( scroll > 50 ){
        		$('.banner_left').css('top', top_bar);
        	}
        	else{
        		$('.banner_left').css('top', top_banner);
        	}
        }

        // set position arrow
        if( $('.banner_right').length > 0 ){
        	var element_wrap = $('body');
        	var offset_wrap = element_wrap.offset();

        	$('.banner_right').css('width', offset_wrap.left);

        	if( scroll > 50 ){
        		$('.banner_right').css('top', top_bar);
        	}
        	else{
        		$('.banner_right').css('top', top_banner);
        	}
        }
    });

    function u() {
        $('.phoneEvent').each(function () {
            var n = $(this).attr('raw');
            $(this).text(n + ' · Sao chép');
            $(this).addClass('showHotline');
        });
    }

    function copyToClipboard(n) {
	    function i(n) {
	        var r = navigator.userAgent.match(/ipad|iphone/i),
	            t,
	            i;
	        if (r) {
	            var u = n.contentEditable,
	                f = n.readOnly,
	                e = document.scrollingElement.scrollTop;
	            n.contentEditable = !0;
	            n.readOnly = !1;
	            t = document.createRange();
	            t.selectNodeContents(n);
	            i = window.getSelection();
	            i.removeAllRanges();
	            i.addRange(t);
	            n.setSelectionRange(0, 999999);
	            n.contentEditable = u;
	            n.readOnly = f;
	            document.scrollingElement.scrollTop = e;
	        } else n.select();
	        document.execCommand("copy");
	    }
	    var t = document.createElement("textarea");
	    t.style.position = "fixed";
	    t.style.top = 0;
	    t.style.left = 0;
	    t.style.width = "2em";
	    t.style.height = "2em";
	    t.style.padding = 0;
	    t.style.border = "none";
	    t.style.outline = "none";
	    t.style.boxShadow = "none";
	    t.style.background = "transparent";
	    t.value = n;
	    document.body.appendChild(t);
	    i(t);
	    try {
	        return document.execCommand("copy");
	    } catch (r) {
	        return console.log("Oops, unable to copy"), !1;
	    } finally {
	        document.body.removeChild(t);
	    }
	}

	function showToolTip(n) {
	    n.hasClass("tooltip") == !1 && (n.addClass("tooltip"), n.append('<dl class="tooltip-text">Đã sao chép</dl>'));
	    $(".tooltip-text").css("display", "none");
	    n.hasClass("tooltip") &&
	        n
	            .children(".tooltip-text")
	            .css("display", "block")
	            .animate({ opacity: "1" }, 1e3, "easeInOutQuint")
	            .delay(800)
	            .animate({ opacity: "0" }, 2e3, "easeInOutQuint", function () {
	                $(this).css("display", "none");
	            });
	}

	jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
		phone_number = phone_number.replace(/\s+/g, "");
		return this.optional(element) || phone_number.length == 10 && 
		phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
	}, "Số điện thoại không đúng");

	jQuery.validator.addMethod("valueNotEquals", function(value, element, arg){
	  	return arg !== value;
	}, "Bạn phải chọn.");

	function formatNumber(nStr, decSeperate, groupSeperate) {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    }

});