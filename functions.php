<?php
/**
重要提示：请将你自己要添加到 functions.php 的所有代码，添加到主题根目录的 custom-functions.php，不要添加到这个文件，不要编辑这个文件！！！！！！！
*/
$themename = "wpdx";
$themefolder = "wpdx";
$my_theme = wp_get_theme();
define ('theme_name', $themename );
define ('theme_ver' ,  $my_theme->get( 'Version' ) );
// Notifier Info
$notifier_name = $themename;
$notifier_url = "http://www.cmhello.com/xml/".$themefolder.".xml";
//Docs Url
$docs_url = "http://www.cmhello.com/wpdx-help.html";
// Constants for the theme name, folder and remote XML url
define( 'MTHEME_NOTIFIER_THEME_NAME', $themename );
define( 'MTHEME_NOTIFIER_THEME_FOLDER_NAME', $themefolder );
define( 'MTHEME_NOTIFIER_XML_FILE', $notifier_url );
define( 'MTHEME_NOTIFIER_CACHE_INTERVAL', 1 );
// Custom Functions
include (TEMPLATEPATH . '/custom-functions.php');
// Get Functions
include (TEMPLATEPATH . '/includes/home-cats.php');
include (TEMPLATEPATH . '/includes/home-cat-tabs.php');
include (TEMPLATEPATH . '/includes/home-cat-scroll.php');
include (TEMPLATEPATH . '/includes/home-cat-pic.php');
include (TEMPLATEPATH . '/includes/home-recent-box.php');
//
include (TEMPLATEPATH . '/includes/pagenavi.php');
include (TEMPLATEPATH . '/includes/breadcrumbs.php');
include (TEMPLATEPATH . '/includes/widgets.php');
include (TEMPLATEPATH . '/functions/theme-functions.php');
include (TEMPLATEPATH . '/functions/wp_bootstrap_navwalker.php');
include (TEMPLATEPATH . '/functions/categories-metas.php');
include (TEMPLATEPATH . '/functions/common-scripts.php');
include (TEMPLATEPATH . '/functions/default-options.php');
include (TEMPLATEPATH . '/functions/updates.php');
if(cmp_get_option( 'lightbox' )) include (TEMPLATEPATH . '/functions/auto-highslide.php');
if(cmp_get_option( 'lazyload' )) include (TEMPLATEPATH . '/functions/my-lazyload.php');
if(cmp_get_option( 'show_ids' )) include (TEMPLATEPATH . '/functions/show-ids.php');
//Add DWQA functions 20140211
if(function_exists('dwqa_plugin_init')) include (TEMPLATEPATH . '/dwqa-templates/dwqa-functions.php');
// cmp-Panel
include (TEMPLATEPATH . '/panel/mpanel-ui.php');
include (TEMPLATEPATH . '/panel/mpanel-functions.php');
//include (TEMPLATEPATH . '/panel/shortcodes/shortcode.php');
//include (TEMPLATEPATH . '/panel/post-options.php');
include (TEMPLATEPATH . '/panel/custom-slider.php');
include (TEMPLATEPATH . '/panel/notifier/update-notifier.php');
// with activate istall option
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {
	if( !get_option('cmp_active') ){
		cmp_save_settings( $default_data );
		update_option( 'cmp_active' , theme_ver );
	}
   //header("Location: admin.php?page=panel");
}
//所有设置结束
/**
重要提示：请将你自己要添加到 functions.php 的所有代码，添加到主题根目录的 custom-functions.php，不要添加到这个文件，不要编辑这个文件！！！！！！！
*/