<?php

if( !get_option('cmp_options') ){
	if( get_option('cmp_active') < 3 ){
	$old_options  = array(
			"cmp_announcement",
			"cmp_user_tips",
			"cmp_favicon",
			"cmp_gravatar",
			"cmp_logo",
			"cmp_dashboard_logo",
			"cmp_time_format",
			"cmp_breadcrumbs",
			"cmp_footer_links",
			"cmp_homepage_keywords",
			"cmp_homepage_description",
			"cmp_on_home",
			"cmp_list_style",
			"cmp_ignore_sticky",
			"cmp_blog_posts_number",
			"cmp_exclude_categories",
			"cmp_exclude_posts",
			"cmp_home_exc_length",
			"cmp_home_tabs_box",
			"cmp_display_social_icon",
			"cmp_sina_weibo",
			"cmp_qq_weibo",
			"cmp_qq",
			"cmp_google_plus",
			"cmp_twitter",
			"cmp_qq_email_list",
			"cmp_send_email",
			"cmp_rss_url",
			"cmp_slider",
			"cmp_images_number",
			"cmp_slider_mode",
			"cmp_slider_auto",
			"cmp_slider_autoHover",
			"cmp_slider_controls",
			"cmp_slider_pager",
			"cmp_slider_captions",
			"cmp_slider_pause",
			"cmp_slider_number",
			"cmp_slider_query",
			"cmp_slider_tag",
			"cmp_slider_posts",
			"cmp_slider_pages",
			"cmp_slider_custom",
			"cmp_post_authorbio",
			"cmp_post_nav",
			"cmp_post_meta",
			"cmp_post_author",
			"cmp_post_date",
			"cmp_post_views",
			"cmp_post_cats",
			"cmp_post_comments",
			"cmp_post_tags",
			"cmp_post_note",
			"cmp_share_post",
			"cmp_bdStyle",
			"cmp_bdSize",
			"cmp_imageShare",
			"cmp_selectShare",
			"cmp_related",
			"cmp_related_number",
			"cmp_related_query",
			"cmp_banner_top",
			"cmp_banner_top_img",
			"cmp_banner_top_url",
			"cmp_banner_top_alt",
			"cmp_banner_top_tab",
			"cmp_banner_top_adsense",
			"cmp_banner_right",
			"cmp_banner_right_img",
			"cmp_banner_right_url",
			"cmp_banner_right_alt",
			"cmp_banner_right_tab",
			"cmp_banner_right_adsense",
			"cmp_banner_above",
			"cmp_banner_above_img",
			"cmp_banner_above_url",
			"cmp_banner_above_alt",
			"cmp_banner_above_tab",
			"cmp_banner_above_adsense",
			"cmp_banner_below",
			"cmp_banner_below_img",
			"cmp_banner_below_url",
			"cmp_banner_below_alt",
			"cmp_banner_below_tab",
			"cmp_banner_below_adsense",
			"cmp_ads1_shortcode",
			"cmp_ads2_shortcode",
			"cmp_right_rolling",
			"cmp_right_one",
			"cmp_right_two",
			"cmp_right_h_one",
			"cmp_right_h_two",
			"cmp_right_p_one",
			"cmp_right_p_two",
			"cmp_hide_sidebar",
			"cmp_sidebar_home",
			"cmp_sidebar_page",
			"cmp_sidebar_post",
			"cmp_sidebar_archive",
			"cmp_default_number",
			"cmp_exc_length",
			"cmp_small_thumb",
			"cmp_small_thumb_number",
			"thumb_left",
			"cmp_big_thumb",
			"cmp_big_thumb_number",
			"cmp_archive_meta",
			"cmp_archive_author",
			"cmp_archive_date",
			"cmp_archive_views",
			"cmp_archive_comments",
			"cmp_archive_tags",
			"cmp_category_desc",
			"cmp_category_rss",
			"cmp_tag_rss",
			"cmp_author_bio",
			"cmp_author_rss",
			"cmp_search_exclude_pages",
			"cmp_theme_color",
			"cmp_custom_css",
			"cmp_css",
			/*"cmp_notify_theme",*/
			"cmp_show_ids",
			"cmp_cache_avatar",
			"cmp_lightbox",
			"cmp_lazyload",
			"cmp_jquery_cdn",
			"cmp_smilies",
			"cmp_comment_mail_notify",
			"cmp_anti_spam",
			"cmp_comment_chinese",
			"cmp_hide_toolbar",
			"cmp_show_login",
			"cmp_password_url",
			"cmp_register_url",
			"cmp_user_menu",
			"cmp_show_qqqun",
			"cmp_qqqun_url",
			"cmp_qqqun_title",
			"cmp_show_qq",
			"cmp_qq_code",
			"cmp_show_weibo",
			"cmp_weibo_uid",
			"cmp_weibo_type",
			"cmp_show_qqweibo",
			"cmp_qqweibo_name",
			"cmp_qqweibo_t",
			"cmp_qqweibo_f",
			"cmp_baidu_search",
			"cmp_search_domain",
			"cmp_search_id",
			"cmp_search_target",
			"cmp_from_email",
			"cmp_from_name",
			"cmp_rand_max",
			"cmp_timthumb",
			"cmp_thumb_zc",
			"cmp_thumb_q"
		);

		$current_options = array();
		foreach( $old_options as $option ){
			if( get_option( $option ) ){
				$new_option = preg_replace('/cmp_/', '' , $option);
				if( $option == 'cmp_home_tabs' ){
					$cmp_home_tabs = explode (',' , get_option( $option ) );
					$current_options[$new_option] = $cmp_home_tabs  ;
				}else{
					$current_options[$new_option] =  get_option( $option ) ;
				}
				update_option( 'cmp_options' , $current_options );
				delete_option($option);
			}
		}
		update_option( 'cmp_active' , 3 );
		echo '<script>location.reload();</script>';
		die;
	}
}
?>