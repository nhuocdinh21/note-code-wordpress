<!-- add custom javascript -->
	<script type="text/javascript">
		jQuery(function($) {

			// add event load more info table price
			$( document ).ready(function() {
				if( $('.term_description').length > 0 ){
					var wrap_parent = $('.term_description');
					var wrap = $('.term_description .description_inner');
	                var current_height = wrap.height();
	                var your_height = 150;
	                if(current_height > your_height){
	                	wrap.css('height', your_height+'px');
	                	wrap_parent.append(function(){
	                        return '<div class="btn_viewmore btn_showmore"><a href="javascript:;"><?php echo __('View more','custom'); ?></a></div>';
	                    });
	                    wrap_parent.append(function(){
	                        return '<div class="btn_viewmore btn_showless" style="display: none;"><a href="javascript:;"><?php echo __('Collapse','custom'); ?></a></div>';
	                    });
	                    $('body').on('click','.btn_showmore', function(){
	                        wrap.removeAttr('style');
	                        $(this).hide();
	                        wrap_parent.find('.btn_showless').show();
	                    });
	                    $('body').on('click','.btn_showless', function(){
	                        wrap.css('height', your_height+'px');
	                        $(this).hide();
	                        wrap_parent.find('.btn_showmore').show();
	                    });
	            	}
				}
		 	});

		});
	</script>
<!-- end custom javascript -->