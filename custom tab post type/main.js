jQuery(function($) {
	// custom tab
	if( $('.tabs').length > 0 ){
		$('.tabs .tab-links .tab a').on('click', function(e) {
			var currentAttrValue = $(this).attr('href');

			// Show/Hide Tabs
			// $('.tabs ' + currentAttrValue).addClass('active');
			// $('.tabs ' + currentAttrValue).siblings().removeClass('active');
			// $('.tabs ' + currentAttrValue).show().siblings().hide();

			$(this).parents('.tabs').find(currentAttrValue).addClass('active');
			$(this).parents('.tabs').find(currentAttrValue).siblings().removeClass('active');
			$(this).parents('.tabs').find(currentAttrValue).show().siblings().hide();

			// Change/remove current tab to active
			$(this).parent('.tab').addClass('active');
			$(this).parent('.tab').siblings().removeClass('active');

			e.preventDefault();
		});
	}

});