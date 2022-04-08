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
      <section class="span8 archive-list">
        <div class="widget-box" role="main" itemscope itemtype="http://schema.org/CollectionPage">
          <header id="archive-head">
            <h1 itemprop="headline">
              <?php echo __('Tag: ','cmp') . single_tag_title( '', false ) ; ?>
              <?php if( cmp_get_option( 'tag_rss' ) ):
              $tag_id = get_query_var('tag_id'); ?>
              <a class="rss-cat-icon tooltip" title="<?php _e( 'Subscribe to this tag', 'cmp' ); ?>"  href="<?php echo  get_term_feed_link($tag_id , 'post_tag', "rss2") ?>"><?php _e( 'Subscribe to this tag', 'cmp' ); ?></a>
            <?php endif; ?>
          </h1>
          <div class="archive-description"><?php echo sprintf( __( 'The following articles associated with the tag: %s', 'cmp' ), single_tag_title('', false)); ?></div>
        </header>
        <?php
        query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
        get_template_part( 'loop', 'tag' ); ?>
      </div>
    </section>
    <?php get_sidebar(); ?>
  </div>
</div>
</div>
<?php get_footer(); ?>