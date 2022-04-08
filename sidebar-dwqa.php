<aside class="span4 sidebar-right <?php if(cmp_get_option( 'hide_sidebar' )) echo 'hide-sidebar'; ?>" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
	<?php
	wp_reset_query();
	if (function_exists('dynamic_sidebar') && dynamic_sidebar('dwqa-widget-area')){

	} else {
		echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "DWQA Widget Area".','cmp').'</div>';
	}
	?>
</aside>