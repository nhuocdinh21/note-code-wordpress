jQuery(function($) {

	// add wrap_list_post
    if( $('.wrap_list_post').length > 0 ){
        // var page = 0;
        $('.wrap_list_post .loadmore_post > a').click(function(e) {         
            var page = $(this).data('page');
            var cat = $(this).data('cat');
            var post_per_page = $(this).data('post-per-page');
            var post_showmore = $(this).data('post-showmore');
            var total = $(this).data('total');
            var taxonomy = $(this).data('taxonomy');
            var posttype = $(this).data('posttype');
            
            $.ajax({ // you can also use $.post here
                url  : custom_params.ajaxurl, // AJAX handler
                data : {
                    'action'          : 'custom_loadmore_post',
                    'offset'          : post_per_page + ( page * post_showmore ),
                    'post_per_page'   : post_showmore,
                    'cat'             : cat,
                    'total'           : total,
                    'taxonomy'        : taxonomy,
                    'posttype'        : posttype,
                },
                type : 'POST',
                dataType: 'json',
                beforeSend : function ( xhr ) {
                    $('#load_more_' + cat).find('i').removeClass('hidden');
                },
                success : function( data ){    
                    if( data ) { 
                        page++; 

                        $('#load_more_' + cat).data('page', page);              

                        $('#load_more_' + cat).find('i').addClass('hidden');

                        $('#list_post_result_' + cat + ' > .row').append(data.html);

                        var current = post_per_page + ( page * post_showmore );

                        if( current >= total ){
                            $('.wrap_list_post_' + cat + ' .loadmore_post').remove();
                        }
                    }
                }
            });
        });
    } 
		

});