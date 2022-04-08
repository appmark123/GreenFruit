<?php
global $get_meta;
if( ( cmp_get_option( 'post_meta' ) && empty( $get_meta["cmp_hide_meta"][0] ) ) || $get_meta["cmp_hide_meta"][0] == 'no' ): ?>		
<p class="post-meta">
	<?php if( cmp_get_option( 'post_author' ) ): ?>		
		<span><i class="icon-user"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) )?>" title="<?php sprintf( esc_attr__( 'View all posts by %s', 'cmp' ), get_the_author() ) ?>"><?php echo get_the_author() ?></a></span>
	<?php endif; ?>	
	<?php if( cmp_get_option( 'post_date' ) && cmp_get_option( 'time_format' ) != 'none' ): ?>		
		<span class="time"><i class="icon-time"></i><?php cmp_get_time() ?></span>
	<?php endif; ?>	
	<?php if( cmp_get_option( 'post_cats' ) ): ?>
		<span class="cat"><i class="icon-folder-open-alt"></i><?php printf('%1$s', get_the_category_list( ', ' ) ); ?></span>
	<?php endif; ?>	
	<?php if( cmp_get_option( 'post_views' ) ): ?>
		<span class="eye"><i class="icon-eye-open"></i><?php if(function_exists('the_views')) { the_views(); } ?></span>
	<?php endif; ?>
	<?php if( cmp_get_option( 'post_comments' ) ): ?>
		<span class="comm"><i class="icon-comment-alt"></i><?php comments_popup_link('0','1','%' ); ?></span>
	<?php endif; ?>
	<?php edit_post_link( __( 'Edit', 'cmp' ), '<span class="edit"><i class="icon-edit"></i>', '</span>' ); ?>
</p>
<div class="clear"></div>
<?php endif; ?>