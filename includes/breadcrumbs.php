<?php

function cmp_breadcrumbs() {
  if(cmp_get_option('breadcrumbs')){
    $delimiter = '<i class="icon-angle-right"></i>';
    $before = '<span class="current">';
    $after = '</span>';

    if ( !is_home() && !is_front_page() || is_paged() ) {

      echo '<div itemscope itemtype="http://schema.org/WebPage" id="breadcrumb">';

      global $post;
      $homeLink = home_url();
      echo ' <a itemprop="breadcrumb" href="' . $homeLink . '" title="'.__( 'Go to Home','cmp').'" class="tip-bottom"><i class="icon-home"></i>' . __( 'Home' , 'cmp' ) . '</a> ' . $delimiter . ' ';

      if ( is_category() || is_tax() ) {
        global $wp_query;
        $cat_obj = $wp_query->get_queried_object();
        $thisCat = $cat_obj->term_id;
        $thisCat = get_category($thisCat);
        $parentCat = get_category($thisCat->parent);
        if ($thisCat->parent != 0){
          $cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
          echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
        }
        echo $before . '' . single_cat_title('', false) . '' . $after;

      } elseif ( is_day() ) {
        echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('d') . $after;

      } elseif ( is_month() ) {
        echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('F') . $after;

      } elseif ( is_year() ) {
        echo $before . get_the_time('Y') . $after;

      } elseif ( is_single() && !is_attachment() ) {
        if ( get_post_type() != 'post' ) {
          $post_type = get_post_type_object(get_post_type());
          $slug = $post_type->rewrite;
          echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
          /*echo $before . get_the_title() . $after;*/
          echo $before . __('article','cmp'). $after;
        } else {
          $cat = get_the_category(); $cat = $cat[0];
          $cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
          echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );

          /*echo $before . get_the_title() . $after;*/
          echo $before . __('article','cmp'). $after;
        }

      } elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        echo $before . $post_type->labels->singular_name . $after;

      } elseif ( is_attachment() ) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID); $cat = $cat[0];
        echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;

      } elseif ( is_page() && !$post->post_parent ) {
        echo $before . get_the_title() . $after;

      } elseif ( is_page() && $post->post_parent ) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          $breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
          $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;

      } elseif ( is_search() ) {
        echo $before ;
        printf( __( 'Search Results for: %s', 'cmp' ),  get_search_query() );
        echo  $after;

      } elseif ( is_tag() ) {
       echo $before ;
       printf( __( 'Tag Archives: %s', 'cmp' ), single_tag_title( '', false ) );
       echo  $after;

     } elseif ( is_author() ) {
/*       global $author;
       $userdata = get_userdata($author);*/
       $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
       echo $before ;
       printf( __( 'Author Archives: %s', 'cmp' ),  $author->display_name );
       echo  $after;

     } elseif ( is_404() ) {
      echo $before;
      _e( 'Not Found', 'cmp' );
      echo  $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
        echo sprintf( __( '( Page %s )', 'cmp' ), get_query_var('paged') );
    }

    echo '</div>';

  }
}
}
?>