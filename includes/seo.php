<title><?php
	global $page, $paged;
	$site_description = get_bloginfo( 'description', 'display' );
	if ( is_home() || is_front_page() ) {
		bloginfo('name');
		if ($site_description) echo " | $site_description";
		if ( $paged >= 2 || $page >= 2 )
			echo ' - ' . sprintf( __( 'Page %s', 'cmp' ), max( $paged, $page ) );
	} elseif(is_single() || is_page()) {
		if(get_post_meta($post->ID, "_post_title_value", true)){
			echo strip_tags(trim(get_post_meta($post->ID, "_post_title_value", true)));
		}else{
			echo trim(wp_title('',0));
		}
		if ( $paged >= 2 || $page >= 2 )
			echo ' - ' . sprintf( __( 'Page %s', 'cmp' ), max( $paged, $page ) );
		echo ' | ' ;
		bloginfo('name');
	}elseif (is_category()) {
		if(get_option('cm_tax_title' . get_query_var('cat'))){
			echo strip_tags(trim(get_option('cm_tax_title' . get_query_var('cat'))));
		}else{
			echo trim(wp_title('',0));
		}
		if ( $paged >= 2 || $page >= 2 )
			echo ' - ' . sprintf( __( 'Page %s', 'cmp' ), max( $paged, $page ) );
		echo ' | ' ;
		bloginfo('name');
	}elseif (is_tax()) {
		$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		if(get_option('cm_tax_title' . $current_term->term_id)){
			echo strip_tags(trim(get_option('cm_tax_title' .$current_term->term_id)));
		}else{
			echo trim(wp_title('',0));
		}
		if ( $paged >= 2 || $page >= 2 )
			echo ' - ' . sprintf( __( 'Page %s', 'cmp' ), max( $paged, $page ) );
		echo ' | ' ;
		bloginfo('name');
	}else{
		echo trim(wp_title('',0));

		if ( $paged >= 2 || $page >= 2 )
			echo ' - ' . sprintf( __( 'Page %s', 'cmp' ), max( $paged, $page ) );
		echo ' | ' ;
		bloginfo('name');
	}
	?></title>
	<?php
	$description ='';
	$keywords ='';
	if (is_home() || is_front_page())
	{
		$description = strip_tags(trim(cmp_get_option('homepage_description')));
		$keywords = cmp_get_option('homepage_keywords');
	}
	elseif (is_category() )
	{
		if(get_option('cm_tax_keywords' . get_query_var('cat'))){
			$keywords = strip_tags(trim(get_option('cm_tax_keywords' . get_query_var('cat'))));
		}else{
			$keywords = single_cat_title('', false);
		}
		$description = strip_tags(trim(wp_trim_words(category_description(),130,"") ));
	}
	elseif (is_tax())
	{
		$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		if(get_option('cm_tax_keywords' . $current_term->term_id)){
			$keywords = strip_tags(trim(get_option('cm_tax_keywords' . $current_term->term_id)));
		}else{
			$keywords = single_cat_title('', false);
		}
		$description = strip_tags(trim(wp_trim_words(category_description(),130,"") ));
	}
	elseif (is_tag())
	{
		$description = strip_tags(trim(sprintf( __( 'The following articles associated with the tag: %s', 'cmp' ), single_tag_title('', false)) ));
		$keywords = single_tag_title('', false);
	}
	elseif (is_single())
	{
		if(get_post_meta($post->ID, "_post_description_value", true)) {
			$description = strip_tags(trim(get_post_meta($post->ID, "_post_description_value", true)));
    	} elseif ($post->post_excerpt) {
    		$description = strip_tags(trim($post->post_excerpt));
    	} else {
    		$description = strip_tags(trim(wp_trim_words($post->post_content, 130,"") ));
	 	};
		if(get_post_meta($post->ID, "_post_keywords_value", true)) {
			$keywords = strip_tags(trim(get_post_meta($post->ID, "_post_keywords_value", true)));
		} else{
			$tags = wp_get_post_tags($post->ID);
    		foreach ($tags as $tag ) {$keywords = $keywords . $tag->name . ", ";}
		}
}
elseif (is_page())
{
	if(get_post_meta($post->ID, "_post_description_value", true)) {
			$description = strip_tags(trim(get_post_meta($post->ID, "_post_description_value", true)));
    	} else {
    		$description = strip_tags(trim(wp_trim_words($post->post_content, 130,"") ));
	 	};
	 	if(get_post_meta($post->ID, "_post_keywords_value", true)) {
			$keywords = strip_tags(trim(get_post_meta($post->ID, "_post_keywords_value", true)));
		} else{
			$keywords = single_post_title('', false);
		}
}
elseif( is_search() )
{
	$keywords = strip_tags(get_search_query());
	$description = sprintf( __( 'Posts 0f search results for: %s', 'cmp' ), get_search_query() );
}
elseif( is_author() )
{
	/*global $author;
	$userdata = get_userdata($author);*/
	$userdata = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$keywords = strip_tags($userdata->display_name);
	$description = sprintf( __( 'Posts 0f author: %s', 'cmp' ),  $userdata->display_name ).'('.strip_tags(trim($userdata->description)).')';
}
?>
<meta name="keywords" content="<?php echo $keywords ?>" />
<?php if ( $paged >= 2 || $page >= 2 ):
	$desc_page = sprintf( __( 'Page %s:', 'cmp' ), max( $paged, $page ) );
	?>
	<meta name="description" content="<?php echo $desc_page.$description ?>" />
<?php else: ?>
	<meta name="description" content="<?php echo $description ?>" />
<?php endif; ?>