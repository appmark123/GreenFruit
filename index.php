<?php get_header(); ?>
<div id="main-content">
  <div id="content-header">
    <div id="top-announce"><i class="icon-bullhorn"></i><?php echo htmlspecialchars_decode(cmp_get_option( 'announcement' ));?></div>
  </div>
  <div class="container-fluid home-fluid">
  <?php //Top Banner
  if(  empty( $get_meta["cmp_hide_top"][0] ) ){
   if( !empty( $get_meta["cmp_banner_top"][0] ) ) echo '<div class="row-fluid ggtop">' .htmlspecialchars_decode($get_meta["cmp_banner_top"][0]) .'</div>';
   else cmp_banner('banner_top' , '<div class="row-fluid ggtop">' , '</div>' );
 }
 ?>
  <?php
  if( is_home() && cmp_get_option( 'slider' )) cmp_include( 'slider' ); ?>
   <div class="row-fluid">
  <?php if( cmp_get_option('on_home') != 'boxes' ){ ?>
  <section class="span8">
  <?php
    $do_not_duplicate = array( cmp_get_option( 'exclude_posts' ));
    $posts_per_page = (cmp_get_option( 'blog_posts_number' )) ? cmp_get_option( 'blog_posts_number' ) : 10;
    if(cmp_get_option( 'ignore_sticky' )) {
      $args = array('ignore_sticky_posts' => 1,'post__not_in' =>$do_not_duplicate,'cat' => cmp_get_option( 'exclude_categories' ),'paged' => $paged,'posts_per_page' => $posts_per_page ); query_posts($args);
    } else {
      $args = array('post__not_in' =>$do_not_duplicate,'cat' => cmp_get_option( 'exclude_categories' ),'paged' => $paged,'posts_per_page' => $posts_per_page ); query_posts($args);
    }
    get_template_part( 'loop', 'blog' );
  ?>
  </section>
  <?php
  get_sidebar();
  }else{
    $cats = get_option( 'cmp_home_cats' ) ;
    if($cats)
      foreach ($cats as $cat) cmp_get_home_cats($cat);
    else _e( 'You can use Homepage builder to build your homepage' , 'cmp' );
    cmp_home_tabs();
  }
  ?>
</div>
</div>
</div>
<?php get_footer(); ?>