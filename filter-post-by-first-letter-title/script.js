// add filter partner
if( $('.filter_partners').length > 0 ){
	var initial_first_letter = $('.filter_partners > ul li:eq(0)').data('letter');
	var posts_results_item = $('.filter_partners #filter-results .partner-item');

	// Initial load of page
	// $('.filter_partners #filter-results .partner-item[data-letter="' + initial_first_letter + '"]').addClass('show');
	$('.filter_partners #filter-results .partner-item').addClass('show');
	$('.filter_partners > ul li:eq(0)').addClass('current');

	var click_first_char;

	var filter_key = $('#filter-results .filter_key span');

	if( initial_first_letter == '#' ){
		initial_first_letter_txt = $('.filter_partners > ul li:eq(0)').text();
	}
	else if( initial_first_letter == 'number' ){
		initial_first_letter_txt = '0-9';
	}
	else{
		initial_first_letter_txt = initial_first_letter;
	}

	filter_key.text(initial_first_letter_txt);

	// Clicking A-Z list items
	$('.filter_partners > ul li').click(function(){			
		$(posts_results_item).removeClass('show'); // Hide all posts
		$('.filter_partners > ul li').removeClass('current'); // Remove current class from all A-Z list items
		click_first_char = $(this).data('letter'); // Add clicked character to click_first_char variable
		$(this).addClass('current'); // Add current class to clicked A-Z list item
		if( click_first_char == '#' ){
			$('.filter_partners #filter-results .partner-item').addClass('show');
			click_first_char_txt = $('.filter_partners > ul li:eq(0)').text();
			filter_key.text(click_first_char_txt);
		}
		else {
			$('.filter_partners #filter-results .partner-item[data-letter="' + click_first_char + '"]').addClass('show'); // Show posts that match clicked character
			if( click_first_char == 'number' ){
				click_first_char_txt = '0-9';
			}
			else{
				click_first_char_txt = click_first_char;
			}
			filter_key.text(click_first_char_txt);
		}
	   
	});
}