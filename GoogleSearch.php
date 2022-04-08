<?php /* Template Name: GoogleSearch */ ?>
<?php get_header(); ?>
	<section id="container">
		<div class="row">
			<?php the_post(); ?>
			<article id="post-box"   <?php post_class( 'span12' ); ?>>
				<div class="panel">
					<header class="panel-header">
<h1 class="alert" style="box-sizing: inherit; margin: 0px 0px 0px; padding: 2px; border-left: 3px solid #47494C; font-size: 28px; font-weight: 400; orphans: 2; widows: 2;"><a class="8" style="text-decoration: none; text-align: center; display: inline-block; cursor: pointer; width: 200px; height: 50px; line-height: 50px; background: #2E3137; color: #ffffff; border-radius: 0px;">文章列表</a></h1>

					</header>
					
					
					
<?php
    $cats = get_categories();
    foreach ( $cats as $cat ) {
    query_posts( 'showposts=30&cat=' . $cat->cat_ID );
?>
   </br> <h1><a class="8" style="text-decoration: none; text-align: center; display: inline-block; cursor: pointer; width: 200px; height: 50px; line-height: 50px; background: #0079BF; color: #ffffff; border-radius: 0px;"><?php echo $cat->cat_name; ?></a></h1></br>
	
    <ul class="sitemap-list">
        <?php while ( have_posts() ) { the_post(); ?>
        <span style="line-height:2;"><li>📍<a href="<?php the_permalink(); ?>"  target="_blank"><?php the_title(); ?></a></li></span> 
        <?php } wp_reset_query(); ?>
    </ul>
<?php } ?>











			<?php if( !post_password_required() && ( comments_open() || get_comments_number() > 0 ) ) comments_template( '', true ); ?>
		</div>
	</section>
<?php get_footer(); ?>