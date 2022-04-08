<div id="main-content">
	<div id="content-header">
		<?php cmp_breadcrumbs();?>
	</div>
	<div class="container-fluid">
<?php //Top Banner
if( empty( $get_meta["cmp_hide_top"][0] ) ){
	if( !empty( $get_meta["cmp_banner_top"][0] ) ) echo '<div class="row-fluid ggtop">' .htmlspecialchars_decode($get_meta["cmp_banner_top"][0]) .'</div>';
	else cmp_banner('banner_top' , '<div class="row-fluid ggtop">' , '</div>' );
}
?>
<div class="row-fluid">
	<div class="span8">
		<div class="widget-box">
			<article class="widget-content single-post" itemscope itemtype="http://schema.org/Article">
				<header id="post-header">
				<?php
					global $dwqa_options;
					$submit_question_link = get_permalink( $dwqa_options['pages']['submit-question'] );
					if( $dwqa_options['pages']['submit-question'] && $submit_question_link &&is_single()) { ?>
					<a href="<?php echo $submit_question_link ?>" class="dwqa-btn dwqa-btn-success ask-q"><?php _e('Ask a question','dwqa') ?></a>
					<?php } ?>
					<div class="page-title" itemprop="headline"><?php _ex('Question', 'post type general name','dwqa'); ?>
					</div>
				</header>
				<div class="qa-entry" itemprop="articleBody">
