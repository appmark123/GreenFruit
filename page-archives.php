<?php
/*
Template Name: archives
*/
?>
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
        <?php if ( ! have_posts() ) : ?>
          <div class="widget-box">
            <article class="widget-content single-post" itemscope itemtype="http://schema.org/Article">
             <header class="entry-header">
              <h1 class="page-title" itemprop="headline"><?php _e( 'Not Found', 'cmp' ); ?></h1>
            </header>
            <div class="entry">
              <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'cmp' ); ?></p>
              <?php get_search_form(); ?>
            </div>
          </article>
        </div>
      <?php endif; ?>

      <?php while ( have_posts() ) : the_post(); ?>
        <div class="widget-box">
          <article class="widget-content single-post" itemscope itemtype="http://schema.org/Article">
            <header id="post-header">
              <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
            </header>
            <div class="entry" itemprop="articleBody">
              <?php the_content(); ?>
              <?php archives_list(); ?>
              <script src="<?php echo get_template_directory_uri(); ?>/js/archives.js"></script>
            </div>
          </article>
        </div>
      <?php endwhile;?>
    </div>
    <?php get_sidebar(); ?>
  </div>
</div>
</div>
<?php get_footer(); ?>