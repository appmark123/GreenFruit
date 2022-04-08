<?php
/**
 * 移除菜单的多余CSS选择器
 * From http://www.wpdaxue.com/remove-wordpress-nav-classes.html
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
	return is_array($var) ? array_intersect($var, array('current-menu-item','current-post-ancestor','current-menu-ancestor','current-menu-parent')) : '';
}
/*-----------------------------------------------------------------------------------*/
# Get Theme Options
/*-----------------------------------------------------------------------------------*/
function cmp_get_option( $name ) {
	$get_options = get_option( 'cmp_options' );
	if( !empty( $get_options[$name] ))
		return $get_options[$name];
	return false ;
}
/*-----------------------------------------------------------------------------------*/
# Setup Theme
/*-----------------------------------------------------------------------------------*/
add_action( 'after_setup_theme', 'cmp_setup' );
function cmp_setup() {
	global $default_data;
	add_theme_support( 'automatic-feed-links' );
	load_theme_textdomain( 'cmp', get_template_directory() . '/languages' );
	register_nav_menus( array(
		'main-menu' => __( 'Main Menu', 'cmp' ),
		'foot-menu' => __( 'Footer Menu', 'cmp' ),
		'foot-link' => __( 'Footer Link', 'cmp' ),
		'user-menu' => __( 'User Menu', 'cmp' ),
		'admin-menu' => __( 'Admin Menu', 'cmp' )
		) );
}
/*-----------------------------------------------------------------------------------*/
# 通过简码调用菜单,例如 [menu name="foot-link"]
# http://www.wpdaxue.com/embed-menu-in-content-shortcode.html
/*-----------------------------------------------------------------------------------*/
function print_menu_shortcode($atts, $content = null) {
	extract(shortcode_atts(array( 'name' => null, ), $atts));
	return wp_nav_menu( array( 'menu' => $name, 'echo' => false ) );
	echo '<div class="clear"></div>';
}
add_shortcode('menu', 'print_menu_shortcode');
/*-----------------------------------------------------------------------------------*/
# Disable WordPress Admin Bar for all users but admins.
/*-----------------------------------------------------------------------------------*/
if(cmp_get_option('hide_toolbar')) show_admin_bar(false);
/*-----------------------------------------------------------------------------------*/
# Check WordPress version  --Added wpdx 1.2
/*-----------------------------------------------------------------------------------*/
add_action('admin_notices', 'wp_version_check_massage');
function wp_version_check_massage(){
	global $wp_version;
	$ver = 3.6;
	if (version_compare($wp_version, $ver) < 0) {
		echo '<div id="message" class="error"><p>'.__("WordPress version you are currently using is less than 3.6, in order to ensure the normal use of the theme, please update to version 3.6 or above.","cmp").'</p></div>';
	}
}
/*-----------------------------------------------------------------------------------*/
# Check  WP-PostViews
/*-----------------------------------------------------------------------------------*/
add_action('admin_notices', 'plugin_check_massage');
function plugin_check_massage(){
	$plugin_messages = array();
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	// Download WP-PostViews plugin
	if(!is_plugin_active( 'wp-postviews/wp-postviews.php' )){
		$plugin_messages[] = __('This theme requires you to install the <strong> WP-PostViews </strong> plugin, <a href="http://wordpress.org/plugins/wp-postviews/" target="_blank">download it from here</a>.','cmp');
	}
	if(count($plugin_messages) > 0){
		echo '<div id="message" class="error">';
			foreach($plugin_messages as $message)
			{
				echo '<p>'.$message.'</p>';
			}
		echo '</div>';
	}
}
/*-----------------------------------------------------------------------------------*/
# Add cuctom content after post  -- Added wpdx 1.2
/*-----------------------------------------------------------------------------------*/
function add_after_post_content($content) {
	global $post;
	if( $post->post_type == "post" && (is_feed() || is_single()) && cmp_get_option('post_note') ) {
		$content .= '<div class="old-message">'.htmlspecialchars_decode(cmp_get_option('post_note')).'</div>';
	}
	return $content;
}
add_filter('the_content', 'add_after_post_content');
/*-----------------------------------------------------------------------------------*/
# Add custom post types archive to nav menus  -- Added wpdx 1.2
# http://www.wpdaxue.com/add-custom-post-types-archive-to-nav-menus.html
/*-----------------------------------------------------------------------------------*/
if( !class_exists('CustomPostTypeArchiveInNavMenu') ) {
	class CustomPostTypeArchiveInNavMenu {
		function CustomPostTypeArchiveInNavMenu() {
			add_action( 'admin_head-nav-menus.php', array( &$this, 'cpt_navmenu_metabox' ) );
			add_filter( 'wp_get_nav_menu_items', array( &$this,'cpt_archive_menu_filter'), 10, 3 );
		}
		function cpt_navmenu_metabox() {
			add_meta_box( 'add-cpt', __('Custom Post Types Archive','cmp'), array( &$this, 'cpt_navmenu_metabox_content' ), 'nav-menus', 'side', 'default' );
		}
		function cpt_navmenu_metabox_content() {
			$post_types = get_post_types( array( 'show_in_nav_menus' => true, 'has_archive' => true ), 'object' );
			if( $post_types ) {
				foreach ( $post_types as &$post_type ) {
					$post_type->classes = array();
					$post_type->type = $post_type->name;
					$post_type->object_id = $post_type->name;
					$post_type->title = $post_type->labels->name;
					$post_type->object = 'cpt-archive';
				}
				$walker = new Walker_Nav_Menu_Checklist( array() );
				echo '<div id="cpt-archive" class="posttypediv">';
				echo '<div id="tabs-panel-cpt-archive" class="tabs-panel tabs-panel-active">';
				echo '<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">';
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $post_types), 0, (object) array( 'walker' => $walker) );
				echo '</ul>';
				echo '</div><!-- /.tabs-panel -->';
				echo '</div>';
				echo '<p class="button-controls">';
				echo '<span class="add-to-menu">';
				echo '<input type="submit"' . disabled( $nav_menu_selected_id, 0 ) . ' class="button-secondary submit-add-to-menu right" value="'. __('Add to menu','cmp') . '" name="add-ctp-archive-menu-item" id="submit-cpt-archive" />';
				echo '<span class="spinner"></span>';
				echo '</span>';
				echo '</p>';
			} else {
				echo __('No Custom Post Types.','cmp');
			}
		}
		function cpt_archive_menu_filter( $items, $menu, $args ) {
			foreach( $items as &$item ) {
				if( $item->object != 'cpt-archive' ) continue;
				$item->url = get_post_type_archive_link( $item->type );
				if( get_query_var( 'post_type' ) == $item->type ) {
					$item->classes[] = 'current-menu-item';
					$item->current = true;
				}
			}
			return $items;
		}
	}
	$CustomPostTypeArchiveInNavMenu = new CustomPostTypeArchiveInNavMenu();
}
/*-----------------------------------------------------------------------------------*/
# get_first_post_date
/*-----------------------------------------------------------------------------------*/
function get_first_post_date($format = "Y-m-d")
{
	$ax_args = array
	(
		'numberposts' => -1,
		'post_status' => 'publish',
		'order' => 'ASC'
		);
	$ax_get_all = get_posts($ax_args);
	$ax_first_post = $ax_get_all[0];
	$ax_first_post_date = $ax_first_post->post_date;
	$output = date($format, strtotime($ax_first_post_date));
	return $output;
}
/*-----------------------------------------------------------------------------------*/
# cmp_avatar_url 在登录、边栏评论、读者墙调用
/*-----------------------------------------------------------------------------------*/
function cmp_avatar_url($mail){
	$p = get_template_directory_uri().'/images/default.png';
	if($mail=='') return $p;
	preg_match("/src='(.*?)'/i", get_avatar( $mail,'45',$p ), $matches);
	return $matches[1];
}
/**
 * 阻止全英文和包含日文的评论
 */
function wpdaxue_comment_post( $incoming_comment ) {
$pattern = '/[一-龥]/u';  // 必须包含汉字
$text = '/['.trim(cmp_get_option( 'sensitive_character' )).']/u';
if(cmp_get_option( 'comment_chinese' ) && !preg_match($pattern, $incoming_comment['comment_content'])) {
wp_die( __("Your comment must contain Chinese.","cmp" ));
}
if(cmp_get_option( 'comment_sensitive' ) && preg_match($text, $incoming_comment['comment_content'])) {
wp_die( __("Comments are not allowed sensitive character.","cmp" ) );
}
return( $incoming_comment );
}
add_filter('preprocess_comment', 'wpdaxue_comment_post');
/*-----------------------------------------------------------------------------------*/
# Post Thumbinals
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) )
	add_theme_support( 'post-thumbnails' );
//输出图片链接
function post_thumbnail_src(){
	global $post;
	if( $values = get_post_custom_values("thumb") ) {	//输出自定义域图片地址
		$values = get_post_custom_values("thumb");
		$post_thumbnail_src = $values [0];
	} elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
		$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$post_thumbnail_src = $thumbnail_src [0];
	} else {
		$post_thumbnail_src = '';
		ob_start();
		ob_end_clean();
		$output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/Ui', $post->post_content, $matches);
		/*$first_img_src = $matches[1];*/
		if(!empty($matches[1])){
			$post_thumbnail_src = $matches[1];   //获取该图片 src
		}else{	//如果日志中没有图片，则显示随机图片
			if(cmp_get_option( 'rand_max' )) {
				$rand_max = cmp_get_option( 'rand_max' );
				$random = mt_rand(1, $rand_max);
			}else{
				$random = mt_rand(1, 5);
			}
			$post_thumbnail_src = get_template_directory_uri().'/images/pic/'.$random.'.jpg';
			//如果日志中没有图片，则显示默认图片
			//$post_thumbnail_src = get_template_directory_uri().'/images/default_thumb.jpg';
		}
	};
	echo $post_thumbnail_src;
}
//输出文章缩略图
function cmp_post_thumbnail($width,$height){
	if (cmp_get_option('timthumb') ){
		$trim = cmp_get_option( 'thumb_zc' );
		$qual = cmp_get_option('thumb_q');
		if(cmp_get_option( 'lazyload' )):
?>
		<img class="lazy" src="<?php echo get_template_directory_uri(); ?>/images/grey.gif" data-original="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src() ?>&amp;h=<?php echo $height ?>&amp;w=<?php echo $width ?>&amp;q=<?php echo $qual ?>&amp;zc=<?php echo $trim ?>&amp;ct=1" alt="<?php the_title(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
		<noscript><img src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src() ?>&amp;h=<?php echo $height ?>&amp;w=<?php echo $width ?>&amp;q=<?php echo $qual ?>&amp;zc=<?php echo $trim ?>&amp;ct=1" alt="<?php the_title(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" /></noscript>
		<?php else: ?>
		<img src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src() ?>&amp;h=<?php echo $height ?>&amp;w=<?php echo $width ?>&amp;q=<?php echo $qual ?>&amp;zc=<?php echo $trim ?>&amp;ct=1" alt="<?php the_title(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
		<?php endif; ?>
	<?php } else { ?>
		<?php if(cmp_get_option( 'lazyload' )): ?>
		<img class="lazy" src="<?php echo get_template_directory_uri(); ?>/images/grey.gif" data-original="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
		<noscript><img src="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" /></noscript>
		<?php else: ?>
		<img src="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
		<?php endif; ?>
	<?php }
}
//输出幻灯专用缩略图
	function cmp_slider_img_src($image_id , $width='' , $height=''){
		global $post;
		if( cmp_get_option( 'timthumb') ){
			$img =  wp_get_attachment_image_src( $image_id , 'full' );
			$trim = cmp_get_option( 'thumb_zc' );
			$qual = cmp_get_option('thumb_q');
			if( !empty($img) ){
				return $img_src = get_template_directory_uri()."/timthumb.php?src=". $img[0] ."&amp;h=".$height ."&amp;w=". $width ."&amp;q=".$qual."&amp;zc=".$trim."&amp;ct=1";
			}
		}else{
			$image_url = wp_get_attachment_image_src($image_id, array($width,$height) );
			return $image_url[0];
		}
	}
	/*-----------------------------------------------------------------------------------*/
# If the menu doesn't exist
	/*-----------------------------------------------------------------------------------*/
	function cmp_nav_fallback(){
		echo '<li class="the_tips">'.__( 'Please Visit "Appearance > Menus" to build menus' , 'cmp' ).'</li>';
	}
	/*-----------------------------------------------------------------------------------*/
# Custom Dashboard login page logo
	/*-----------------------------------------------------------------------------------*/
	function cmp_login_logo(){
		if( cmp_get_option('dashboard_logo') )
			echo '<style  type="text/css"> h1 a {  background-image:url('.cmp_get_option('dashboard_logo').')  !important; } </style>';
	}
	add_action('login_head',  'cmp_login_logo');
	/*-----------------------------------------------------------------------------------*/
# Custom Gravatar
	/*-----------------------------------------------------------------------------------*/
	function cmp_custom_gravatar ($avatar) {
		$cmp_gravatar = cmp_get_option( 'gravatar' );
		if($cmp_gravatar){
			$custom_avatar = cmp_get_option( 'gravatar' );
			$avatar[$custom_avatar] = "Custom Gravatar";
		}
		return $avatar;
	}
	add_filter( 'avatar_defaults', 'cmp_custom_gravatar' );
	/*-----------------------------------------------------------------------------------*/
# Custom Favicon
	/*-----------------------------------------------------------------------------------*/
	function cmp_favicon() {
		$default_favicon = get_template_directory_uri()."/favicon.ico";
		$custom_favicon = cmp_get_option('favicon');
		$favicon = (empty($custom_favicon)) ? $default_favicon : $custom_favicon;
		echo '<link rel="shortcut icon" href="'.$favicon.'" title="Favicon" />';
	}
	add_action('wp_head', 'cmp_favicon');
	/*-----------------------------------------------------------------------------------*/
# no self ping
	/*-----------------------------------------------------------------------------------*/
	function no_self_ping(&$links) {
		$home = home_url();
		foreach ($links as $l => $link )
			if (0 === strpos($link, $home))
				unset($links[$l]);
		}
		add_action( 'pre_ping', 'no_self_ping' );
		/*-----------------------------------------------------------------------------------*/
# remove wptexturize
		/*-----------------------------------------------------------------------------------*/
		remove_filter('the_content', 'wptexturize');
		remove_filter('the_excerpt', 'wptexturize');
		remove_filter('comment_text', 'wptexturize');
		/*-----------------------------------------------------------------------------------*/
# image_default_link_type as file
		/*-----------------------------------------------------------------------------------*/
		update_option('image_default_link_type' , 'file');
		/*-----------------------------------------------------------------------------------*/
# clean head
		/*-----------------------------------------------------------------------------------*/
		function cmp_remove_version() {
			return '';
		}
		add_filter('the_generator', 'cmp_remove_version');
remove_action('wp_head', 'index_rel_link');//当前文章的索引
remove_action('wp_head', 'feed_links_extra', 3);// 额外的feed,例如category, tag页
remove_action('wp_head', 'start_post_rel_link', 10, 0);// 开始篇
remove_action('wp_head', 'parent_post_rel_link', 10, 0);// 父篇
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // 上、下篇.
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );//rel=pre
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );//rel=shortlink
remove_action('wp_head', 'rel_canonical' );
//wp_deregister_script('l10n');
/*-----------------------------------------------------------------------------------*/
# add_contact_fields on profile
/*-----------------------------------------------------------------------------------*/
add_filter( 'user_contactmethods', 'cm_add_contact_fields' );
function cm_add_contact_fields( $contactmethods ) {
	$contactmethods['qq'] = __('QQ','cmp');
	$contactmethods['qm_mailme'] = __('QQ Mail Me','cmp');
	$contactmethods['qq_weibo'] = __('QQ Weibo','cmp');
	$contactmethods['sina_weibo'] = __('Sina Weibo','cmp');
	$contactmethods['twitter'] = __('Twitter','cmp');
	$contactmethods['google_plus'] = __('Google+','cmp');
	$contactmethods['donate'] = __('Donate url','cmp');
	unset( $contactmethods['yim'] );
	unset( $contactmethods['aim'] );
	unset( $contactmethods['jabber'] );
	return $contactmethods;
}
/*-----------------------------------------------------------------------------------*/
# Author Box --used in single post、archive and sidebar
/*-----------------------------------------------------------------------------------*/
function cmp_author_box($avatar = true , $social = true ){
	if( $avatar ) : ?>
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'MFW_author_bio_avatar_size', 64 ) ); ?>
	</div><!-- #author-avatar -->
<?php endif; ?>
<div class="author-description">
	<p><?php if(get_the_author_meta( 'description' )){
		echo get_the_author_meta( 'description' );
	}else{
		$current_user = wp_get_current_user();
		$author_id = get_the_author_meta( 'ID' );
		if(class_exists("WP_User_Frontend") && $current_user->ID == $author_id){
			$profile_url = get_home_url().'/user/profile';
			$description = sprintf(__('Please visit <a href="%s">Your Profile</a> to fill in Biographical Info.','cmp'),$profile_url);
		}elseif($current_user->ID == $author_id){
			$profile_url = get_home_url().'/wp-admin/profile.php';
			$description = sprintf(__('Please visit <a href="%s">Your Profile</a> to fill in Biographical Info.','cmp'),$profile_url);
		}else{
			$description = __('The user is lazy, not fill in his Biographical Info.','cmp');
		}
		echo $description;
	}
	?>
	</p>
	<?php  if( $social ) :	?>
		<ul class="author-social follows nb">
			<?php  if( !is_author() ) :
				$userlinks = get_author_posts_url( get_the_author_meta( 'ID' ) );
			?>
			<li class="archive">
				<a target="_blank" href="<?php echo $userlinks; ?>" title="<?php echo sprintf( __( "Read more articles of %s", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Read more articles of %s", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( get_the_author_meta( 'donate' ) ) : ?>
			<li class="donate">
				<a target="_blank" rel="nofollow" href="<?php the_author_meta( 'donate' ); ?>" title="<?php echo sprintf( __( "Donate to %s", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Donate to %s", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
		<?php if ( get_the_author_meta( 'url' ) ) : ?>
			<li class="website">
				<a target="_blank" rel="nofollow" href="<?php the_author_meta( 'url' ); ?>" title="<?php echo sprintf( __( "Visit %s's site", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Visit %s's site", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
		<?php if ( get_the_author_meta( 'qq' ) ) : ?>
			<li class="qq">
				<a target="_blank" rel="nofollow" href="http://wpa.qq.com/msgrd?V=1&Menu=yes&Uin=<?php the_author_meta( 'qq' ); ?>" title="<?php echo sprintf( __( "Contact %s by QQ", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Contact %s by QQ", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
		<?php if(class_exists("cartpaujPM")){ ?>
			<li class="email">
				<a target="_blank" rel="nofollow" href="<?php echo get_home_url(); ?>/user/pm?pmaction=newmessage&to=<?php echo get_the_author_meta( 'ID' ); ?>" title="<?php echo sprintf( __( "Contact %s by Private Messages", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Contact %s by Private Messages", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php } elseif(get_the_author_meta( 'qm_mailme' )) { ?>
			<li class="email">
				<a target="_blank" rel="nofollow" href="<?php the_author_meta( 'qm_mailme' ); ?>" title="<?php echo sprintf( __( "Contact %s by Email", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Contact %s by Email", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php } ?>
		<?php if ( get_the_author_meta( 'sina_weibo' ) ) : ?>
			<li class="sina_weibo">
				<a target="_blank" rel="nofollow" href="<?php the_author_meta( 'sina_weibo' ); ?>" title="<?php echo sprintf( __( "Follow %s on Sina Weibo", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on Sina Weibo", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
		<?php if ( get_the_author_meta( 'qq_weibo' ) ) : ?>
			<li class="qq_weibo">
				<a target="_blank" rel="nofollow" href="<?php the_author_meta( 'qq_weibo' ); ?>" title="<?php echo sprintf( __( "Follow %s on QQ Weibo", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on QQ Weibo", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
		<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
			<li class="twitter">
				<a target="_blank" rel="nofollow" href="<?php the_author_meta( 'twitter' ); ?>" title="<?php echo sprintf( __( "Follow %s on Twitter", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on Twitter", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
		<?php if ( get_the_author_meta( 'google_plus' ) ) : ?>
			<li class="google_plus">
				<a target="_blank" href="<?php the_author_meta( 'google_plus' ); ?>" rel="author" title="<?php echo sprintf( __( "Follow %s on Google+", 'cmp' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on Google+", 'cmp' ), get_the_author_meta( 'display_name' )); ?></a>
			</li>
		<?php endif ?>
	</ul>
</div><!-- #author-description -->
<?php endif; ?>
<div class="clear"></div>
<?php
}
/*-----------------------------------------------------------------------------------*/
# color tags Cloud
/*-----------------------------------------------------------------------------------*/
function colorCloud($text) {
	$text = preg_replace_callback('|<a (.+?)>|i','colorCloudCallback', $text);
	return $text;
}
function colorCloudCallback($matches) {
	$text = $matches[1];
	$color = dechex(rand(0,16777215));
	$pattern = '/style=(\'|\”)(.*)(\'|\”)/i';
	$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
	return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);
/*-----------------------------------------------------------------------------------*/
# custom cache avatar -- based on themes setting
/*-----------------------------------------------------------------------------------*/
function my_avatar($avatar) {
	$tmp = strpos($avatar, 'http');
	$g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
	$tmp = strpos($g, 'avatar/') + 7;
	$f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);
	$w = home_url();
	$e = preg_replace('/wordpress\//', '', ABSPATH) .'avatar/'. $f .'.jpg';
	$t = 604800; //设定7天, 单位:秒
	if ( empty($default) ) $default = $w. '/avatar/default.jpg';
	if ( !is_file($e) || (time() - filemtime($e)) > $t ) //当头像不存在或者文件超过7天才更新
	copy(htmlspecialchars_decode($g), $e);
	else
		$avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.jpg'));
	if (filesize($e) < 500) copy($default, $e);
	return $avatar;
}
// Check if avatar directory exists --add 1.3
$default_avatar = preg_replace('/wordpress\//', '', ABSPATH) .'avatar/default.jpg';
if (cmp_get_option('cache_avatar') && is_file($default_avatar)){
	add_filter('get_avatar', 'my_avatar');
}
/*-----------------------------------------------------------------------------------*/
# Archives list by zwwooooo | http://zww.me
/*-----------------------------------------------------------------------------------*/
function archives_list() {
	if( !$output = get_option('archives_list') ){
		$output = '<div id="archives"><p>[<a id="al_expand_collapse" rel="nofollow" href="#">'.__('Expand / Collapse All </a>] <em>(Note: Click on the month you can expand it)</em>','cmp').'</p>';
		$the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' ); //update: 加上忽略置顶文章
		$year=0; $mon=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
		$year_tmp = get_the_time('Y');
		$mon_tmp = get_the_time('m');
		$y=$year; $m=$mon;
		if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
		if ($year != $year_tmp && $year > 0) $output .= '</ul>';
		if ($year != $year_tmp) {
			$year = $year_tmp;
				$output .= '<h3 class="al_year">'. $year .__(' Year','cmp').'</h3><ul class="al_mon_list">'; //输出年份
			}
			if ($mon != $mon_tmp) {
				$mon = $mon_tmp;
				$output .= '<li><span class="al_mon">'. $mon .__(' Month','cmp').'</span><ul class="al_post_list">'; //输出月份
			}
			$output .= '<li>'. get_the_time('d') .__(' Day: ','cmp').'<a href="'. get_permalink() .'">  '. get_the_title() .'</a> <em>('. get_comments_number('0', '1', '%') .')</em></li>'; //输出文章日期和标题
			endwhile;
			wp_reset_postdata();
			$output .= '</ul></li></ul></div>';
			update_option('archives_list', $output);
		}
		echo $output;
	}
	function clear_al_cache() {
	update_option('archives_list', ''); // 清空 zww_archives_list
}
add_action('save_post', 'clear_al_cache'); // 新发表文章/修改文章时
/*-----------------------------------------------------------------------------------*/
# Custom comment style
/*-----------------------------------------------------------------------------------*/
function cm_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$comorder =  get_option('comment_order');
	if($comorder == 'asc'){
		//在页面顶部显示 旧的 评论
		global $commentcount;
		if(!$commentcount) { //初始化楼层计数器
			$page = get_query_var('cpage')-1;
			$cpp=get_option('comments_per_page');//获取每页评论数
			$commentcount = $cpp * $page;
		}
	}else{
		//在页面顶部显示 新的 评论
		global $commentcount,$wpdb, $post;
		if(!$commentcount) {
			$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
			$cnt = count($comments);
			$page = get_query_var('cpage');
			$cpp=get_option('comments_per_page');
			if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
				$commentcount = $cnt + 1;
			} else {$commentcount = $cpp * $page + 1;}
		}
	}
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
			<?php $add_below = 'div-comment'; ?>
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 54 , '',$comment->comment_author); ?>
				<div class="floor">
					<?php
					if($comorder == 'asc'){
			//在页面顶部显示 旧的 评论
						if(!$parent_id = $comment->comment_parent){printf('%1$s#', ++$commentcount);}
					}else{
			//在页面顶部显示 新的 评论
						if(!$parent_id = $comment->comment_parent){printf('%1$s#', --$commentcount);}
					}
					?>
				</div>
				<?php comment_author_link() ?>:<?php edit_comment_link(__('Edit','cmp'),'&nbsp;&nbsp;',''); ?>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<span><?php _e('Your comment is awaiting moderation ...','cmp') ?></span>
				<br />
			<?php endif; ?>
			<?php comment_text() ?>
			<div class="clear"></div>
			<span class="datetime"><?php comment_date('Y-m-d') ?> <?php comment_time() ?> </span>
			<span class="reply"><?php comment_reply_link(array_merge( $args, array('reply_text' => __('[Reply]','cmp'), 'add_below' =>$add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
		</div>
		<?php
	}
	function cm_end_comment() {echo '</li>';}
	/*-----------------------------------------------------------------------------------*/
# custom_smilies_src
	/*-----------------------------------------------------------------------------------*/
	function custom_smilies_src($src, $img){
		return get_template_directory_uri().'/images/smilies/' . $img;
	}
	add_filter('smilies_src', 'custom_smilies_src', 10, 2);
	/*-----------------------------------------------------------------------------------*/
# anti_spam 垃圾评论拦截
	/*-----------------------------------------------------------------------------------*/
	if (cmp_get_option('anti_spam')){
		class anti_spam {
			function anti_spam() {
				if ( !is_user_logged_in() ) {
					add_action('template_redirect', array($this, 'w_tb'), 1);
					add_action('pre_comment_on_post', array($this, 'gate'), 1);
					add_action('preprocess_comment', array($this, 'sink'), 1);
				}
			}
	//設欄位
			function w_tb() {
				if ( is_singular() ) {
					ob_start(create_function('$input', 'return preg_replace("#textarea(.*?)name=([\"\'])comment([\"\'])(.+)/textarea>#",
						"textarea$1name=$2w$3$4/textarea><textarea name=\"comment\" cols=\"60\" rows=\"4\" style=\"display:none\"></textarea>", $input);') );
				}
			}
	//檢查
			function gate() {
				( !empty($_POST['w']) && empty($_POST['comment']) ) ? $_POST['comment'] = $_POST['w'] : $_POST['spam_confirmed'] = 1;
			}
	//處理
			function sink( $comment ) {
				if ( !empty($_POST['spam_confirmed']) ) {
			//方法一:直接擋掉, 將 die(); 前面兩斜線刪除即可.
					die();
			//方法二:標記為spam, 留在資料庫檢查是否誤判.
			//add_filter('pre_comment_approved', create_function('', 'return "spam";'));
			//$comment['comment_content'] = "[ 小牆判斷這是Spam! ]\n" . $comment['comment_content'];
				}
				return $comment;
			}
		}
		$anti_spam = new anti_spam();
	}
	/*-----------------------------------------------------------------------------------*/
# Custom mail from
	/*-----------------------------------------------------------------------------------*/
	function cmp_from_email($email) {
		if (cmp_get_option('from_email')) {
			$wp_from_email = cmp_get_option('from_email');
		}else{
			$wp_from_email = get_option('admin_email');
		}
		return $wp_from_email;
	}
	function cmp_mail_from_name($email){
		if (cmp_get_option('from_name')) {
			$wp_from_email = cmp_get_option('from_name');
		}else{
			$wp_from_name = get_option('blogname');
		}
		return $wp_from_name;
	}
	add_filter('wp_mail_from', 'cmp_from_email');
	add_filter('wp_mail_from_name', 'cmp_mail_from_name');
	/*-----------------------------------------------------------------------------------*/
# comment_mail_notify
	/*-----------------------------------------------------------------------------------*/
	function comment_mail_notify($comment_id) {
	$admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
	$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
	$comment = get_comment($comment_id);
	$comment_author_email = trim($comment->comment_author_email);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	global $wpdb;
	if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
		$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
	if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
		$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
	$notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
	$spam_confirmed = $comment->comment_approved;
	if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
	$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 发出点, no-reply 可改为可用的 e-mail.
	$to = trim(get_comment($parent_id)->comment_author_email);
	$subject = sprintf( __( 'Hi! Someone just reply to your comments on %s', 'cmp' ), get_option("blogname") );
	$message = '
	<div style="color:#333;font:100 14px/24px microsoft yahei;">
		<p>' . sprintf( __( 'Hi! %s', 'cmp' ), trim(get_comment($parent_id)->comment_author) ) . '</p>
		<p>' . sprintf( __( 'Your comments on %s :', 'cmp' ), get_the_title($comment->comment_post_ID) ) . '<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
			. trim(get_comment($parent_id)->comment_content) . '</p>
			<p>' . trim($comment->comment_author) . __(' Reply to you:','cmp').'<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
				. trim($comment->comment_content) . '<br /></p>
				<p>' . sprintf( __( 'Click <a href="%s">HERE</a> for more details.', 'cmp' ), htmlspecialchars(get_comment_link($parent_id)) ) . '</p>
				<p>'.__('Welcome back to','cmp').'<a href="' . home_url() . '">' . get_option('blogname') . '</a></p>
				<p style="color:#999">('.__('This message is sent automatically by the system, do not reply to.','cmp').')</p>
			</div>';
			$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
			$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
			wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
		}
	}
//自动勾选
	function cmp_add_checkbox() {
		global $post;
		if($post->post_type != 'dwqa-question' ){
			echo '<p><label for="comment_mail_notify"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>'.__('E-mail me when someone replies to me.','cmp').'</label></p>';
		}
	}
	if (cmp_get_option('comment_mail_notify')) {
		add_action('comment_post','comment_mail_notify');
		add_action('comment_form','cmp_add_checkbox');
	}
	/*-----------------------------------------------------------------------------------*/
# Get Home Cats Boxes
	/*-----------------------------------------------------------------------------------*/
	function cmp_get_home_cats($cat_data){
		switch( $cat_data['type'] ){
			case 'n':
			get_home_cats( $cat_data );
			break;
			case 's':
			get_home_scroll( $cat_data );
			break;
			case 'news-pic':
			get_home_news_pic( $cat_data );
			break;
			case 'recent':
			get_home_recent( $cat_data );
			break;
			case 'divider': ?>
			<div class="divider" style="height:<?php echo $cat_data['height'] ?>px"></div>
			<div class="clear"></div>
			<?php
			break;
			case 'ads': ?>
			<div class="home-ads"><?php echo do_shortcode( htmlspecialchars_decode(stripslashes ($cat_data['text']) )) ?></div>
			<div class="clear"></div>
			<?php
			break;
		}
	}
	/*-----------------------------------------------------------------------------------*/
# Exclude pages From Search
	/*-----------------------------------------------------------------------------------*/
	function cmp_Search_Filter($query) {
		if( $query->is_search ){
			if ( cmp_get_option( 'search_exclude_pages' ) && !is_admin() )
				$query->set('post_type', 'post');
			if ( cmp_get_option( 'search_cats' ))
				$query->set( 'cat', cmp_get_option( 'search_cats' ) && !is_admin() );
		}
		return $query;
	}
	add_filter('pre_get_posts','cmp_Search_Filter');
	/*-----------------------------------------------------------------------------------*/
# Excerpt Length
	/*-----------------------------------------------------------------------------------*/
	function cmp_excerpt_global_length( $length ) {
		if( cmp_get_option( 'exc_length' ) )
			return cmp_get_option( 'exc_length' );
		else return 84;
	}
	function cmp_excerpt_home_length( $length ) {
		if( cmp_get_option( 'home_exc_length' ) )
			return cmp_get_option( 'home_exc_length' );
		else return 60;
	}
	function cmp_excerpt(){
		add_filter( 'excerpt_length', 'cmp_excerpt_global_length', 999 );
		echo get_the_excerpt();
	}
	function cmp_excerpt_home(){
		add_filter( 'excerpt_length', 'cmp_excerpt_home_length', 999 );
		echo get_the_excerpt();
	}
	/*-----------------------------------------------------------------------------------*/
# Read More Functions
	/*-----------------------------------------------------------------------------------*/
	function cmp_remove_excerpt( $more ) {
		return ' ...';
	}
	add_filter('excerpt_more', 'cmp_remove_excerpt');
	/*-----------------------------------------------------------------------------------*/
# Page Navigation
	/*-----------------------------------------------------------------------------------*/
	function cmp_pagenavi(){
		?>
		<div class="page-nav">
			<?php cmp_get_pagenavi() ?>
		</div>
		<div class="clear"></div>
		<?php
	}
	/*-----------------------------------------------------------------------------------*/
# Get templates
	/*-----------------------------------------------------------------------------------*/
	function cmp_include($template){
		include ( get_template_directory() . '/includes/'.$template.'.php' );
	}
	/*-----------------------------------------------------------------------------------*/
# News In Picture
	/*-----------------------------------------------------------------------------------*/
	function wp_last_news_pic($order , $numberOfPosts = 12 , $cats = 1 ){
		global $post;
		$orig_post = $post;
		if( $order == 'random')
			$lastPosts = get_posts(	$args = array('numberposts' => $numberOfPosts, 'orderby' => 'rand', 'category' => $cats ));
		else
			$lastPosts = get_posts(	$args = array('numberposts' => $numberOfPosts, 'category' => $cats ));
		get_posts('category='.$cats.'&numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post); ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
			<?php cmp_post_thumbnail(210,120) ?>
		</a>
	<?php endforeach;
	$post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Most Racent posts
/*-----------------------------------------------------------------------------------*/
function wp_last_posts($numberOfPosts = 5 , $thumb = true){
	global $post;
	$orig_post = $post;
	$lastPosts = get_posts('numberposts='.$numberOfPosts);
	foreach($lastPosts as $post): setup_postdata($post);
	?>
	<li>
		<div class="widget-thumb">
			<?php if ( $thumb ) : ?>
				<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
					<?php cmp_post_thumbnail(45,45) ?>
				</a>
			<?php endif; ?>
			<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
			<span class="date"><?php cmp_get_time(); ?></span>
		</div>
	</li>
<?php endforeach;
$post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Most Racent posts from Category
/*-----------------------------------------------------------------------------------*/
function wp_last_posts_cat($numberOfPosts = 5 , $thumb = true , $cats = 1){
	global $post;
	$orig_post = $post;
	$lastPosts = get_posts('category='.$cats.'&numberposts='.$numberOfPosts);
	foreach($lastPosts as $post): setup_postdata($post);
	?>
	<li>
		<div class="widget-thumb">
			<?php if ( $thumb ) : ?>
				<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
					<?php cmp_post_thumbnail(45,45) ?>
				</a>
			<?php endif; ?>
			<a href="<?php the_permalink(); ?>"><?php the_title();?></a>
			<span class="date"><?php cmp_get_time() ?></span>
		</div>
	</li>
<?php endforeach;
$post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Random posts
/*-----------------------------------------------------------------------------------*/
function wp_random_posts($numberOfPosts = 5 , $thumb = true){
	global $post;
	$orig_post = $post;
	$lastPosts = get_posts('orderby=rand&numberposts='.$numberOfPosts);
	foreach($lastPosts as $post): setup_postdata($post);
	?>
	<li>
		<div class="widget-thumb">
			<?php if ( $thumb ) : ?>
				<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
					<?php cmp_post_thumbnail(45,45) ?>
				</a>
			<?php endif; ?>
			<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
			<span class="date"><?php cmp_get_time(); ?></span>
		</div>
	</li>
<?php endforeach;
$post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Popular posts
/*-----------------------------------------------------------------------------------*/
function wp_popular_posts($pop_posts = 5 , $thumb = true , $days = 30){
	global $wpdb , $post;
	$orig_post = $post;
	$today = date("Y-m-d H:i:s");
	$daysago = date( "Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60) );
	$popularposts = "SELECT ID,post_title,post_date,post_author,post_content,post_type FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_date BETWEEN '$daysago' AND '$today' ORDER BY comment_count DESC LIMIT 0,".$pop_posts;
	$posts = $wpdb->get_results($popularposts);
	if($posts){
		global $post;
		foreach($posts as $post){
			setup_postdata($post);?>
			<li>
				<div class="widget-thumb">
					<?php if ( $thumb ) : ?>
						<a class="post-thumbnail" href="<?php echo get_permalink( $post->ID ) ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<?php cmp_post_thumbnail(45,45) ?>
						</a>
					<?php endif; ?>
					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
					<span class="date"><?php cmp_get_time(); ?></span>
				</div>
			</li>
			<?php
		}
	}
	$post = $orig_post;
}
//most_comm_posts
function most_comm_posts($mode = '', $nums=10 , $days=7, $display = true) { //$days参数限制时间值，单位为‘天’，默认是7天；$nums是要显示文章数量
	global $wpdb;
	$today = date("Y-m-d H:i:s"); //获取今天日期时间
	$daysago = date( "Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60) );  //Today - $days
	$where = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$result = $wpdb->get_results("SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts WHERE post_date BETWEEN '$daysago' AND '$today' AND $where AND post_status = 'publish' ORDER BY comment_count DESC LIMIT 0 , $nums");
	$output = '';
	if(empty($result)) {
		$output = '<li>'.__('No items in the selected time period.', 'cmp').'</li>'."\n";
	} else {
		foreach ($result as $topten) {
			$postid = $topten->ID;
			$title = $topten->post_title;
			$commentcount = $topten->comment_count;
			if ($commentcount != 0) {
				if($display) {
					$output .= '<li><span>'.$commentcount.' ℃</span><a href="'.get_permalink($postid).'" title="'.$title.' ('.$commentcount.'条评论)'.'"><i class="icon-angle-right"></i>'.$title.'</a></li>';
				} else {
					$output .= '<li><a href="'.get_permalink($postid).'" title="'.$title.' ('.$commentcount.'条评论)'.'"><i class="icon-angle-right"></i>'.$title.'</a></li>';
				}
			}
		}
	}
	echo $output;
}
//Get TimeSpan Most Viewed
function most_viewed_posts($mode = '', $limit = 8, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			if($display) {
				$temp .= '<li class="most-view"><span>'.$post_views.' ℃</span><a href="'.get_permalink().'"><i class="icon-angle-right"></i>'.$post_title.'</a></li>';
			} else {
				$temp .= '<li><a href="'.get_permalink().'"><i class="icon-angle-right"></i>'.$post_title.'</a></li>';
			}
		}
	} else {
		$temp = '<li>'.__('No items in the selected time period.', 'cmp').'</li>'."\n";
	}
	echo $temp;
}
/*-----------------------------------------------------------------------------------*/
# Get the post time
/*-----------------------------------------------------------------------------------*/
function cmp_get_time(){
	global $post ;
	if( cmp_get_option( 'time_format' ) == 'none' ){
		return false;
	}elseif( cmp_get_option( 'time_format' ) == 'modern' ){
		/*$to = time();*/
		$to = current_time('timestamp',1);
		$from = get_the_time('U') ;
		$diff = (int) abs($to - $from);
		if ($diff <= 3600) {
			$mins = round($diff / 60);
			if ($mins <= 1) {
				$mins = 1;
			}
		$since = sprintf(_n('%s min', '%s mins', $mins), $mins) . __( 'ago' , 'cmp' );
		}else if (($diff <= 86400) && ($diff > 3600)) {
			$hours = round($diff / 3600);
			if ($hours <= 1) {
				$hours = 1;
			}
			$since = sprintf(_n('%s hour', '%s hours', $hours), $hours) . __( 'ago' , 'cmp' );
		}elseif ($diff >= 86400) {
			$days = round($diff / 86400);
			if ($days <= 1) {
				$days = 1;
				$since = sprintf(_n('%s day', '%s days', $days), $days) . __( 'ago' , 'cmp' );
			}
			elseif( $days > 29){
				$since = get_the_time(get_option('date_format'));
			}
			else{
				$since = sprintf(_n('%s day', '%s days', $days), $days) . __( 'ago' , 'cmp' );
			}
		}
	}else{
		$since = get_the_time(get_option('date_format'));
	}
	echo $since ;
}
/*-----------------------------------------------------------------------------------*/
# Show Categories on setting page
/*-----------------------------------------------------------------------------------*/
function show_category() {
	global $wpdb;
	$request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
	$request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
	$request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
	$request .= " ORDER BY term_id asc";
	$categorys = $wpdb->get_results($request);
	foreach ($categorys as $category) { //调用菜单
		$output = '<span>'.$category->name."(<em>".$category->term_id.'</em>)</span>';
		echo $output;
	}
}
/*-----------------------------------------------------------------------------------*/
#  Banners
/*-----------------------------------------------------------------------------------*/
function cmp_banner( $banner , $before= false , $after = false){
	if(cmp_get_option( $banner )):
		echo $before;
	?>
	<?php
	if(cmp_get_option( $banner.'_img' )):
		$target="";
	if( cmp_get_option( $banner.'_tab' )) $target='target="_blank"'; ?>
	<a href="<?php echo cmp_get_option( $banner.'_url' ) ?>" rel="nofollow" title="<?php echo cmp_get_option( $banner.'_alt') ?>" <?php echo $target ?>>
		<img src="<?php echo cmp_get_option( $banner.'_img' ) ?>" alt="<?php echo cmp_get_option( $banner.'_alt') ?>" />
	</a>
<?php elseif(cmp_get_option( $banner.'_adsense' )): ?>
	<?php echo htmlspecialchars_decode(cmp_get_option( $banner.'_adsense' )) ?>
	<?php
	endif;
	?>
	<?php
	echo $after;
	endif;
}
/*-----------------------------------------------------------------------------------*/
#  Ads Shortcode
/*-----------------------------------------------------------------------------------*/
## Ads1 -------------------------------------------------- #
function cmp_shortcode_ads1( $atts, $content = null ) {
	$out ='<div class="ads-in-post1">'. htmlspecialchars_decode(cmp_get_option( 'ads1_shortcode' )) .'</div>';
	return $out;
}
add_shortcode('ads1', 'cmp_shortcode_ads1');
## Ads2 -------------------------------------------------- #
function cmp_shortcode_ads2( $atts, $content = null ) {
	$out ='<div class="ads-in-post2">'. htmlspecialchars_decode(cmp_get_option( 'ads2_shortcode' )) .'</div>';
	return $out;
}
add_shortcode('ads2', 'cmp_shortcode_ads2');
?>