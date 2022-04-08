<?php
add_action('wp_enqueue_scripts', 'lazyload_scripts');
add_filter('the_content', 'lazyload_content');
add_filter('wp_get_attachment_link', 'lazyload_content');
add_filter('get_avatar', 'lazyload_content');
add_action('wp_head', 'lazyload_header');
function lazyload_scripts() {
	wp_enqueue_script(	'lazyload',	get_template_directory_uri() . '/js/lazyload.min.js',array(),'1.9.0');
}
function lazyload_header() {
	echo '
<script>
	jQuery(document).ready(function($) {
		$("img.lazy, .entry img, img.avatar").show().lazyload({placeholder : "'.get_template_directory_uri() .'/images/grey.gif",
			effect: "fadeIn",
			failure_limit : 30
		});
	});
</script>';
}
function lazyload_content($content) {
	if( is_feed() || is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() ) || is_admin() )
	{
		return $content;
	}else{
		$loadimg_url = get_template_directory_uri() .'/images/grey.gif';
		$content=preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',"<img\$1data-original=\"\$2\" src=\"$loadimg_url\"\$3>\n<noscript>\$0</noscript>",$content);
	}
	return $content;
}