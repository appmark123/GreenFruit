<?php
/*
Template Name: 用户中心页面
*/
get_header(); ?>
<div id="main-content">
  <div id="content-header">
    <?php cmp_breadcrumbs();?>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
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
        <div class="widget-box user-center">
          <div id="user-left">
          <div class="user-avatar">
            <?php global $userdata; echo get_avatar( $userdata->user_email, 100 ).'<p>'.$userdata->display_name.'</p>'; ?>
          </div>
            <ul id="user-menu">
             <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'user-menu', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker())); ?>
           </ul>
         </div>
         <div class="widget-content single-post" id="user-right" itemscope itemtype="http://schema.org/Article">
          <div id="post-header">
            <div class="feedback"><a href="<?php home_url(); ?>/user/pm?pmaction=newmessage&to=1"><i class="icon-pencil"></i> 反馈建议</a></div>
            <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
            <?php
            if(cmp_get_option('user_tips')){
              echo '<div class="poptip"><span class="poptip-arrow poptip-arrow-left"><em>◆</em><i>◆</i></span>'.htmlspecialchars_decode(cmp_get_option('user_tips')).'</div>';
            }
            ?>
          </div>
          <div class="entry" itemprop="articleBody">
            <?php the_content(); ?>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    <?php endwhile;?>
  </div>
  <?php //get_sidebar(); ?>
</div>
</div>
</div>
<?php get_footer(); ?>