<?php
/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'cmp_register' );
function cmp_register() {
  if(cmp_get_option('jquery_cdn') =='default'){
    wp_enqueue_script( 'jquery' );
  }else{
    wp_deregister_script( 'jquery' );
    if(cmp_get_option('jquery_cdn') == 'jquery') {
      $jquery_cdn = '//code.jquery.com/jquery-1.10.2.min.js';
      $jquery_migrate_cdn = '//code.jquery.com/jquery-migrate-1.2.1.js';
    }elseif (cmp_get_option('jquery_cdn') == 'google') {
      $jquery_cdn = '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'mrosoft') {
      $jquery_cdn = '//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js';
      $jquery_migrate_cdn = '//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'baidu') {
      $jquery_cdn = '//libs.baidu.com/jquery/1.8.3/jquery.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'sae') {
      $jquery_cdn = '//lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js';
      $jquery_migrate_cdn = '//lib.sinaapp.com/js/jquery.migrate/1.2.1/jquery-migrate-1.2.1.min.js';
    } elseif (cmp_get_option('jquery_cdn') == 'upyun') {
      $jquery_cdn = '//upcdn.b0.upaiyun.com/libs/jquery/jquery-1.8.3.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'qiniu') {
      $jquery_cdn = '//cdn.staticfile.org/jquery/1.8.3/jquery.min.js';
    }
    if(isset($jquery_cdn)) wp_register_script( 'jquery',$jquery_cdn, false, theme_ver );
    wp_enqueue_script( 'jquery' );
    if(isset($jquery_migrate_cdn) && $jquery_migrate_cdn){
      wp_register_script( 'jquery-migrate-cdn',$jquery_migrate_cdn, false,'1.2.1' );
      wp_enqueue_script( 'jquery-migrate-cdn' );
    }
  }
  wp_register_script( 'base-js', get_template_directory_uri() . '/js/base.js', array('jquery'), theme_ver );
  wp_enqueue_script( 'base-js' );
  if ( is_home() || is_active_widget( '', '', 'slider-widget' ) ) {
    wp_register_script( 'BxSlider', get_template_directory_uri() . '/js/BxSlider.min.js', array('jquery'), '4.1' );
    wp_enqueue_script( 'BxSlider' );
  }
  wp_register_style( 'font-awesome', get_template_directory_uri() . '/font-awesome/css/font-awesome.min.css','','3.2.1' );
  wp_register_style( 'default', get_template_directory_uri() . '/style.css','',theme_ver );
  wp_enqueue_style( 'font-awesome' );
  wp_enqueue_style( 'default' );
}
/*-----------------------------------------------------------------------------------*/
# Cmp Wp Head
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'cmp_wp_head');
function cmp_wp_head() {
  echo '
  <!--[if lt IE 9]>
  <script src="'.get_template_directory_uri().'/js/html5.js"></script>
  <script src="'. get_template_directory_uri().'/js/css3-mediaqueries.js"></script>
  <![endif]-->
  <!--[if IE 8]>
  <link rel="stylesheet" href="'. get_template_directory_uri().'/css/ie8.css">
  <![endif]-->
  <!--[if IE 7]>
  <link rel="stylesheet" href="'. get_template_directory_uri().'/font-awesome/css/font-awesome-ie7.min.css">
  <link rel="stylesheet" href="'. get_template_directory_uri().'/css/ie7.css">
  <![endif]-->
  <!--[if IE 6]>
  <script src="//letskillie6.googlecode.com/svn/trunk/2/zh_CN.js"></script>
  <![endif]-->
  ';
  if(cmp_get_option('theme_color') && cmp_get_option('theme_color') !=='default' ){
    echo '
    <link rel="stylesheet" type="text/css" media="all" href="'. get_template_directory_uri().'/css/style-'.cmp_get_option("theme_color").'.css" />';
  }
  if(cmp_get_option('logo')){
    echo '<style type="text/css" media="screen">';
    echo '#logo .logoimg{background:url("'.cmp_get_option('logo').'") no-repeat scroll 0 0 transparent;}';
    echo '</style>';
  }
  if(cmp_get_option('custom_css')){
    echo '<style type="text/css" media="screen">';
    echo htmlspecialchars_decode( cmp_get_option('css') ) , "\n";
    echo '</style>';
  }
  if( cmp_get_option('header_code') ){
    echo htmlspecialchars_decode( cmp_get_option('header_code') ) , "\n";
  }
}
/*-----------------------------------------------------------------------------------*/
# Cmp Wp Footer
/*-----------------------------------------------------------------------------------*/
add_action('wp_footer', 'cmp_wp_footer');
function cmp_wp_footer() {
  if( cmp_get_option('right_rolling') ){
    if( is_single() ){
      $r_1 = cmp_get_option('right_one')?cmp_get_option('right_one'):0;;
      $r_2 = cmp_get_option('right_two')?cmp_get_option('right_two'):0;;
    }elseif( is_home() || is_front_page() ){
      $r_1 = cmp_get_option('right_h_one')?cmp_get_option('right_h_one'):0;
      $r_2 = cmp_get_option('right_h_two')?cmp_get_option('right_h_two'):0;
    }elseif( is_page() ){
      $r_1 = cmp_get_option('right_p_one')?cmp_get_option('right_p_one'):0;
      $r_2 = cmp_get_option('right_p_two')?cmp_get_option('right_p_two'):0;
    }else{
      $r_1 = 0;
      $r_2 = 0;
    }
    echo '<script>var right_1 = '.$r_1.',right_2 = '.$r_2.';</script>';
    echo '<script src="'.get_template_directory_uri().'/js/post.js"></script>';
  }
  if(is_singular() &&  comments_open()) echo '<script src="'.get_template_directory_uri().'/comments-ajax.js"></script>';
  //baidu share
  if( cmp_get_option('share_post')){
    if( cmp_get_option( 'bdStyle' ) == '1'){
      $bdStyle = 1;
    }elseif( cmp_get_option( 'bdStyle' ) == '2'){
      $bdStyle = 2;
    }else{
      $bdStyle = 0;
    }
    if( cmp_get_option( 'bdSize' ) == '16'){
      $bdSize = 16;
    }elseif( cmp_get_option( 'bdSize' ) == '32'){
      $bdSize = 32;
    }else{
      $bdSize = 24;
    }
    $bdShare = '
    <script>
      window._bd_share_config={
        "common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"'.$bdStyle.'","bdSize":"'.$bdSize.'"
      },"share":{}';
      if(cmp_get_option('imageShare')) $bdShare .='
        ,"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"} ';
      if(cmp_get_option('selectShare')) $bdShare .='
        ,"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}';
      $bdShare .='
      }; with(document)0[(getElementsByTagName("head")[0]||body).appendChild(createElement("script")).src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion="+~(-new Date()/36e5)];
    </script>';
    echo $bdShare;
  }
if( cmp_get_option('show_weibo')){ ?>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
<?php }
}
?>