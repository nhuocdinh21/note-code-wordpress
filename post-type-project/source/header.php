<!DOCTYPE html>
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="ie9 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<style>
	body
	{
		font-size: <?php echo get_field('fontsize_desktop','option'); ?>px !important;
	}
	@media (max-width: 549px)
	{
		body
		{
			font-size: <?php echo get_field('fontsize_mobile','option'); ?>px !important;
		}
	}
</style>

<body <?php body_class(); // Body classes is added from inc/helpers-frontend.php ?>>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'flatsome' ); ?></a>

<div id="wrapper">

<?php do_action('flatsome_before_header'); ?>

<?php
	if( is_archive() ):	

		if( is_search() ):
			$banner_super_src = get_field('banner_super','option');
			$banner_super_link = get_field('lien_ket_banner_super','option');

			$banner_super_mobile_src = get_field('banner_super_mobile','option');
			$banner_super_mobile_link = get_field('lien_ket_banner_super','option');
		else:
			$obj = get_queried_object();
			$banner_super_src = get_field('banner_super', $obj->taxonomy . '_' . $obj->term_id);
			$banner_super_link = get_field('lien_ket_banner_super', $obj->taxonomy . '_' . $obj->term_id);

			$banner_super_mobile_src = get_field('banner_super_mobile', $obj->taxonomy . '_' . $obj->term_id);
			$banner_super_mobile_link = get_field('lien_ket_banner_super', $obj->taxonomy . '_' . $obj->term_id);
		endif;
	else:
		if( is_singular('du-an') ):			
			$banner_super_src = get_field('banner_super');
			$banner_super_link = get_field('lien_ket_banner_super');

			$banner_super_mobile_src = get_field('banner_super_mobile');
			$banner_super_mobile_link = get_field('lien_ket_banner_super');

			// banner_super_src
			if( $banner_super_src ):
				$banner_super_src = $banner_super_src;
			else:
				$banner_super_src = get_field('banner_super','option');
			endif;

			// banner_super_link
			if( $banner_super_link ):
				$banner_super_link = $banner_super_link;
			else:
				$banner_super_link = get_field('lien_ket_banner_super','option');
			endif;

			// banner_super_mobile_src
			if( $banner_super_mobile_src ):
				$banner_super_mobile_src = $banner_super_mobile_src;
			else:
				$banner_super_mobile_src = get_field('banner_super_mobile','option');
			endif;

			// banner_super_mobile_link
			if( $banner_super_mobile_link ):
				$banner_super_mobile_link = $banner_super_mobile_link;
			else:
				$banner_super_mobile_link = get_field('lien_ket_banner_super','option');
			endif;
		else:
			if( is_search() ):
				$banner_super_src = get_field('banner_super','option');
				$banner_super_link = get_field('lien_ket_banner_super','option');

				$banner_super_mobile_src = get_field('banner_super_mobile','option');
				$banner_super_mobile_link = get_field('lien_ket_banner_super','option');
			else:
				$banner_super_src = get_field('banner_super');
				$banner_super_link = get_field('lien_ket_banner_super');

				$banner_super_mobile_src = get_field('banner_super_mobile');
				$banner_super_mobile_link = get_field('lien_ket_banner_super');
			endif;			
		endif;
	endif;
?>

<?php if( $banner_super_src ): ?>
	<div class="banner_super hide-for-small">
		<a href="<?php echo esc_url( $banner_super_link ); ?>" target="_blank">
			<img src="<?php echo $banner_super_src; ?>">
		</a>
	</div>
<?php endif; ?>

<?php if( $banner_super_mobile_src ): ?>
	<div class="banner_super show-for-small">
		<a href="<?php echo esc_url( $banner_super_mobile_link ); ?>" target="_blank">
			<img src="<?php echo $banner_super_mobile_src; ?>">
		</a>
	</div>
<?php endif; ?>

<header id="header" class="header <?php flatsome_header_classes();  ?>">
   <div class="header-wrapper">
	<?php
		get_template_part('template-parts/header/header', 'wrapper');
	?>
   </div><!-- header-wrapper-->
</header>

<?php do_action('flatsome_after_header'); ?>

<?php if( is_page_template('page-account.php') || is_page_template('page-manage-projects.php') || is_page_template('page-upproject.php') || is_page_template('page-update-project.php') ): ?>
	<?php if( has_nav_menu( 'my_account' ) ) : ?>
		<div class="menu_account_wrap">
			<div class="container">
				<?php 
					wp_nav_menu( array(
						'theme_location' => 'my_account',
						'menu_class'     => 'menu nav menu_account',
						'fallback_cb'    => 'false',
						'container'      => false,
				    ) );
				?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<main id="main" class="<?php flatsome_main_classes();  ?>">
	<?php 
		if( is_archive() ):
			if( is_search() ):
				if( have_rows('banner_showcase','option') ): ?>
					<div class="container">
						<div class="banner_showcase hide-for-small owl-carousel">
							<?php while( have_rows('banner_showcase','option') ) : the_row(); ?>
								<div class="item">
									<div class="inner">
										<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif;
				if( have_rows('banner_showcase_mobile','option') ): ?>
					<div class="container">
						<div class="banner_showcase_mobile show-for-small owl-carousel">
							<?php while( have_rows('banner_showcase_mobile','option') ) : the_row(); ?>
								<div class="item">
									<div class="inner">
										<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif;
			else:
				$obj = get_queried_object();
				if( have_rows('banner_showcase', $obj->taxonomy . '_' . $obj->term_id) ): ?>
					<div class="container">
						<div class="banner_showcase hide-for-small owl-carousel">
							<?php while( have_rows('banner_showcase', $obj->taxonomy . '_' . $obj->term_id) ) : the_row(); ?>
								<div class="item">
									<div class="inner">
										<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif;

				if( have_rows('banner_showcase_mobile', $obj->taxonomy . '_' . $obj->term_id) ): ?>
					<div class="container">
						<div class="banner_showcase_mobile show-for-small owl-carousel">
							<?php while( have_rows('banner_showcase_mobile', $obj->taxonomy . '_' . $obj->term_id) ) : the_row(); ?>
								<div class="item">
									<div class="inner">
										<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif;
			endif;			
		else:	

			if( is_singular('du-an') ):
				if( have_rows('banner_showcase') ): ?>
					<div class="container">
						<div class="banner_showcase hide-for-small owl-carousel">
							<?php while( have_rows('banner_showcase') ) : the_row(); ?>
								<div class="item">
									<div class="inner">
										<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php else:
					if( have_rows('banner_showcase','option') ): ?>
						<div class="container">
							<div class="banner_showcase hide-for-small owl-carousel">
								<?php while( have_rows('banner_showcase','option') ) : the_row(); ?>
									<div class="item">
										<div class="inner">
											<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endif;

				if( have_rows('banner_showcase_mobile') ): ?>
					<div class="container">
						<div class="banner_showcase_mobile show-for-small owl-carousel">
							<?php while( have_rows('banner_showcase_mobile') ) : the_row(); ?>
								<div class="item">
									<div class="inner">
										<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php else:
					if( have_rows('banner_showcase_mobile','option') ): ?>
						<div class="container">
							<div class="banner_showcase_mobile show-for-small owl-carousel">
								<?php while( have_rows('banner_showcase_mobile','option') ) : the_row(); ?>
									<div class="item">
										<div class="inner">
											<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endif;
			else:
				if( is_search() ):
					if( have_rows('banner_showcase','option') ): ?>
						<div class="container">
							<div class="banner_showcase hide-for-small owl-carousel">
								<?php while( have_rows('banner_showcase','option') ) : the_row(); ?>
									<div class="item">
										<div class="inner">
											<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php endif;
					if( have_rows('banner_showcase_mobile','option') ): ?>
						<div class="container">
							<div class="banner_showcase_mobile show-for-small owl-carousel">
								<?php while( have_rows('banner_showcase_mobile','option') ) : the_row(); ?>
									<div class="item">
										<div class="inner">
											<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php endif;
				else:
					if( have_rows('banner_showcase') ): ?>
						<div class="container">
							<div class="banner_showcase hide-for-small owl-carousel">
								<?php while( have_rows('banner_showcase') ) : the_row(); ?>
									<div class="item">
										<div class="inner">
											<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php endif;

					if( have_rows('banner_showcase_mobile') ): ?>
						<div class="container">
							<div class="banner_showcase_mobile show-for-small owl-carousel">
								<?php while( have_rows('banner_showcase_mobile') ) : the_row(); ?>
									<div class="item">
										<div class="inner">
											<a href="<?php echo get_sub_field('lien_ket'); ?>" target="_blank"><img src="<?php echo get_sub_field('hinh_anh'); ?>"></a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php endif;
				endif;				

			endif;
		endif;
	?>

	<?php if( !is_front_page() && !is_tax('danh-muc') && !is_search() && !is_page_template('page-account.php') && !is_page_template('page-manage-projects.php') && !is_page_template('page-upproject.php') && !is_page_template('page-update-project.php') ): ?>
		<div class="cs_breadcumbs">
			<div class="container">
				<div class="breadcumb">
					<?php echo do_shortcode('[wpseo_breadcrumb]'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
