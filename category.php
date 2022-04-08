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
      	<?php $category_id = get_query_var('cat') ; ?>
        <div class="widget-box" role="main" itemscope itemtype="http://schema.org/CollectionPage">
          <header id="archive-head">
            <h1 itemprop="headline">
              <?php echo __('Category: ','cmp') . single_cat_title( '', false ) ; ?>
              <?php if( cmp_get_option( 'category_rss' ) ): ?>
                <a class="rss-cat-icon" title="<?php _e( 'Subscribe to this category', 'cmp' ); ?>" href="<?php echo get_category_feed_link($category_id) ?>"><i class="icon-rss"></i></a>
              <?php endif; ?>
            </h1>
            <?php
            if(cmp_get_option( 'category_desc' ) ):
              $category_description = category_description();
            if(!empty( $category_description ) )
              echo '<div class="archive-description">' . $category_description . '</div>';
            endif;
            ?>
          </header>
          <?php
          // 不同分类存档每页显示不同文章数
          if ( is_category(explode(',', cmp_get_option('big_thumb') )) ){
            query_posts($query_string . "&posts_per_page=".cmp_get_option('big_thumb_number'));
          } elseif ( is_category(explode(',', cmp_get_option('small_thumb') )) ){
            query_posts($query_string . "&posts_per_page=".cmp_get_option('small_thumb_number'));
          } else {
            query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
          }
          get_template_part( 'loop', 'category' );  ?>
        </div>
      </section>
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>