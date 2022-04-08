<?php get_header(); ?>
<div id="main-content">
  <div id="content-header">
    <?php cmp_breadcrumbs();?>
  </div>
  <div class="container-fluid">
  <?php //Top Banner
if(  empty( $get_meta["cmp_hide_top"][0] ) ){
   if( !empty( $get_meta["cmp_banner_top"][0] ) ) echo '<div class="row-fluid ggtop">' .htmlspecialchars_decode($get_meta["cmp_banner_top"][0]) .'</div>';
   else cmp_banner('banner_top' , '<div class="row-fluid ggtop">' , '</div>' );
}
?>
    <div class="row-fluid">
      <div class="span8">
        <div class="widget-box">
          <article class="widget-content single-post" itemscope itemtype="http://schema.org/Article">
            <header id="post-header">
              <h1 class="post-title" itemprop="headline"><?php _e( 'Not Found', 'cmp' ); ?></h1>
              <div class="clear"></div>
              <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'cmp' ); ?></p>
            </header>
            <div class="entry" itemprop="articleBody">
              <?php get_search_form(); ?>
            </div>
          </article>
        </div>
      </div>
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>