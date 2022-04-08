<?php
/**
 * custom permalink to id.html
 * http://www.wpdaxue.com/custom-post-type-permalink-code.html
 */
add_filter('post_type_link', 'custom_qa_link', 1, 3);
function custom_qa_link( $link, $post = 0 ){
	if ( $post->post_type == 'dwqa-question' ){
		return home_url( 'question/' . $post->ID .'.html' );
	} else {
		return $link;
	}
}
add_action( 'init', 'custom_qa_rewrites_init' );
function custom_qa_rewrites_init(){
	add_rewrite_rule(
		'question/([0-9]+)?.html$',
		'index.php?post_type=dwqa-question&p=$matches[1]',
		'top' );
}

/*function hwl_home_pagesize( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_post_type_archive( 'dwqa-question' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'posts_per_page', 50 );
        return;
    }
}
add_action( 'pre_get_posts', 'hwl_home_pagesize', 999 );*/
/**
  * load style for DW Q&A plugin
  */
if( !function_exists('dwqa_simplex_scripts') ){
	function dwqa_simplex_scripts(){
		wp_enqueue_style( 'dw-simplex-qa', get_stylesheet_directory_uri() . '/dwqa-templates/style.css' );
	}
	add_action( 'wp_enqueue_scripts', 'dwqa_simplex_scripts' );
}
/**
 * Widgets For DWQA
 */
add_action( 'widgets_init', 'dwqa_widgets_init' );
function dwqa_widgets_init() {
	$before_widget =  '<div id="%1$s" class="widget-box widget %2$s">';
	$after_widget  =  '</div></div>';
	$before_title  =  '<div class="widget-title"><span class="icon"><i class="icon-list"></i></span><h3>';
	$after_title   =  '</h3></div><div class="widget-content">';
	register_sidebar( array(
		'name' =>  __( 'DWQA Widget Area', 'cmp' ),
		'id' => 'dwqa-widget-area',
		'description' => __( 'The DW Questions & Answer widget area', 'cmp' ),
		'before_widget' => $before_widget , 'after_widget' => $after_widget ,
		'before_title' => $before_title , 'after_title' => $after_title ,
		) );
}