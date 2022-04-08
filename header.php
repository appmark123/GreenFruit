<!DOCTYPE html>
<html <?php language_attributes(); if( cmp_get_option('show_weibo')) echo ' xmlns:wb="http://open.weibo.com/wb"';?>>
<head>
	<?php get_template_part( "includes/seo" ) ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
	<!--Header-part-->
	<header id="header" role="banner">

			<!--close-Header-part-->

			<!--sidebar-menu-->
			
			<nav id="sidebar" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement"> <a href="#" class="visible-phone"><i class="icon icon-fullscreen"></i><?php _e('Navigation menu','cmp');?></a>
			<h1 style="text-align:center;">
	<span style="font-size:16px;font-family:Verdana;"><a href="https://hallo365.top" >hallo365.top</a></span><br><br><br><br>
</h1>
				<ul>
					<?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s','theme_location' => 'main-menu', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker() )) ; ?>
				</ul>
			</nav>
		</header>