<?php 
	$invoice_list = '';
	foreach( get_field('danh_sach_anh_hoa_don') as $invoice ):
		$invoice_list .= '{ "link" : "'.$invoice['url'].'" },';
	endforeach;
?>
<div id="list_invoice">
	<div id="data_container"></div>
</div>						

<script type="text/javascript">
	jQuery(function($) {		
		let container = $('#list_invoice');
		container.pagination({
	      	dataSource: [<?php echo $invoice_list; ?>],
	      	pageSize: <?php echo (get_field('so_luong_hoa_don_hien_thi_tren_1_trang')) ? get_field('so_luong_hoa_don_hien_thi_tren_1_trang') : 4 ; ?>,
			autoHidePrevious: true,
			autoHideNext: true,
	      	callback: function(response, pagination) {							      		
		        let dataHtml = `<div class="row large-columns-4 medium-columns-2 small-columns-2">`;
		        	$.each(response, function (index, item) {
		          		dataHtml += ` 	<div class="col">
		          							<div class="col-inner">
		          								<a href="`+item.link+`" data-fancybox>
		          									<div class="box-image">
		          										<div class="image-cover">
		          											<img src="`+item.link+`" />
		          										</div>
		          									</div>
		          								</a>
		          							</div>
	          					  		</div>
		          		`;

			        });
		        dataHtml += `</div>`;

		        $('#data_container').html(dataHtml);
	      	},
	      	afterPreviousOnClick : function() {
		        if( $('#list_invoice').length > 0 ) {
					$('html, body').animate({
						scrollTop: $('#list_invoice').offset().top - 50
					} ,500);
				}
		    },
	      	afterPageOnClick : function() {
		        if( $('#list_invoice').length > 0 ) {
					$('html, body').animate({
						scrollTop: $('#list_invoice').offset().top - 50
					} ,500);
				}
		    },
		    afterNextOnClick : function() {
		        if( $('#list_invoice').length > 0 ) {
					$('html, body').animate({
						scrollTop: $('#list_invoice').offset().top - 50
					} ,500);
				}
		    }
	    });

	});								
</script>