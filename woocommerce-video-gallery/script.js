// show image when product has video
if( $('.product_has_video').length > 0 ){
	$('.product_has_video > img').hide();
}	

jQuery(document).on( 'found_variation', 'form.cart', function( event, variation ) {
	if( $('.product_has_video').length > 0 ){
    	$('.product_has_video > iframe').hide();
		$('.product_has_video > img').show();
	}
	$('.product-thumbnails .col > a .icon_video').hide();
});		

$(document).on('click','.reset_variations',function(){
    if( $('.product_has_video').length > 0 ){
    	$('.product_has_video > iframe').show();
		$('.product_has_video > img').hide();
	}
	$('.product-thumbnails .col > a .icon_video').show();
});