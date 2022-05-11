<?php if( have_rows('menu_scroll_section_trang_doanh_nghiep') ): ?>
	<div class="page_template_scrollto_section hide-for-medium">
		<div class="container">
			<ul>
				<?php while( have_rows('menu_scroll_section_trang_doanh_nghiep') ) : the_row(); ?>
					<li>
						<a href="#<?php echo get_sub_field('scroll_to'); ?>"><?php echo get_sub_field('title'); ?></a>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
	</div>
	<div class="scroll_topbar"></div>
<?php endif; ?>