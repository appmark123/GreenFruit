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
        <div class="widget-box" role="main" itemscope itemtype="http://schema.org/SearchResultsPage">
          <div id="archive-head">
          <?php if ( have_posts() ) : ?>
            <header class="page-header">
              <h1 itemprop="headline">
                  <?php printf( __( 'Search: %s', 'cmp' ),  get_search_query() ); ?>
              </h1>
            </header>
            <div class="archive-description"><?php printf( __( 'Posts 0f search results for: %s', 'cmp' ), get_search_query()); ?></div>
            <?php else : ?>
              <header class="page-header">
              <h1 itemprop="headline">
                  <?php echo __( 'Nothing Found', 'cmp' ); ?>
              </h1>
            </header>
            <div class="archive-description"><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'cmp' ); ?></p></div>
                <?php endif; ?>
          </div>
          <?php
          query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
           if ( have_posts() ) : ?>
            <?php get_template_part( 'loop', 'search' );  ?>
          <?php else : ?>
            <article id="post-0" class="post not-found">
              <div class="entry">
                <?php get_search_form(); ?>
              </div>
            </article>
          <?php endif; ?>
        </div>
      </section>
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>