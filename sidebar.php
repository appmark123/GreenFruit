<aside class="span4 sidebar-right <?php if(cmp_get_option( 'hide_sidebar' )) echo 'hide-sidebar'; ?>" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
<br>
<?php
if(cmp_get_option( 'baidu_search' )){
	$action = 'http://'.cmp_get_option( 'search_domain' ).'/cse/search';
	$search_id = '<input type="hidden" name="s" value="'.cmp_get_option( 'search_id' ).'">';
	$name = 'q';
}else{
	$action = home_url();
	$search_id = '';
	$name = 's';
}
?>

<div id="search">
	<form class="search_form" method="GET" action="<?php echo $action ?>" <?php if(cmp_get_option( 'search_target' )) echo 'target="_blank"'; ?> >
		<?php echo $search_id ?>
		<input class="left" type="text" name="<?php echo $name ?>" id="search-txt" value="<?php _e( 'Search …' , 'cmp' ) ?>" onfocus="if (this.value == '<?php _e( 'Search …' , 'cmp' ) ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Search …' , 'cmp' ) ?>';}" x-webkit-speech />
		<button type="submit" class="tip-bottom" title="<?php _e( 'Search' , 'cmp' ) ?>"><i class="icon-search icon-white"></i></button>
	</form>
</div>
<br>
近日文章
<br>



<?php
 
  wp_reset_query();
  if ( is_home() ){

    $sidebar_home = cmp_get_option( 'sidebar_home' );
    if( $sidebar_home ){
      dynamic_sidebar ( sanitize_title( $sidebar_home ) );
    } elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){

    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','cmp').'</div>';
    }

  }elseif( is_page() ){
    global $get_meta;
    $cmp_sidebar_pos = $get_meta["cmp_sidebar_pos"][0];

    if( $cmp_sidebar_pos != 'full' ){
      $cmp_sidebar_post = sanitize_title($get_meta["cmp_sidebar_post"][0]);
      $sidebar_page = cmp_get_option( 'sidebar_page' );
      if( $cmp_sidebar_post ){
        dynamic_sidebar($cmp_sidebar_post);
      }

      elseif( $sidebar_page ){
        dynamic_sidebar ( sanitize_title( $sidebar_page ) );
      }

      elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){

      } else {
        echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','cmp').'</div>';
      }
    }

  }elseif ( is_single() ){
    global $get_meta;
    $cmp_sidebar_pos = $get_meta["cmp_sidebar_pos"][0];

    if( $cmp_sidebar_pos != 'full' ){
      $cmp_sidebar_post = sanitize_title($get_meta["cmp_sidebar_post"][0]);
      $sidebar_post = cmp_get_option( 'sidebar_post' );
      if( $cmp_sidebar_post )
        dynamic_sidebar($cmp_sidebar_post);

      elseif( $sidebar_post )
        dynamic_sidebar ( sanitize_title( $sidebar_post ) );

      elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){

      } else {
        echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','cmp').'</div>';
      }
    }

  }elseif ( is_category() ){

    $category_id = get_query_var('cat') ;
    $cat_sidebar = cmp_get_option( 'sidebar_cat_'.$category_id ) ;
    $sidebar_archive = cmp_get_option( 'sidebar_archive' );

    if( $cat_sidebar )
      dynamic_sidebar ( sanitize_title( $cat_sidebar ) );

    elseif( $sidebar_archive )
      dynamic_sidebar ( sanitize_title( $sidebar_archive ) );

    elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){

    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','cmp').'</div>';
    }

  }else{
    $sidebar_archive = cmp_get_option( 'sidebar_archive' );
    if( $sidebar_archive ){
      dynamic_sidebar ( sanitize_title( $sidebar_archive ) );
    }
    elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){

    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','cmp').'</div>';
    }
  }
  ?>
  
  
  
  
</aside>