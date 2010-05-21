<?php
/**
 * @package Portfolio
 * @subpackage Display
 */
get_header(); ?>
	<?php setup_postdata($post); ?>
	<div id="portfolio">
		<div class="images">
			<div id="primary" class="loading">
			</div>
			<ul id="thumbs">
			</ul>	
		</div>
		<div class="entry">
			<h2><?php the_title(); ?></h2>
			<h4><strong>CATEGORY:</strong>&nbsp;<a href="<?php echo $post->category->link; ?>"><?php echo $post->category->name; ?></a></h4>
			<h4><strong>STATUS:</strong>&nbsp;<?php echo $post->status; ?></h4>
			<p><strong>CLIENT:</strong> <?php echo $post->client; ?></p>
			<p><strong>DESIGN:</strong> <a href="<?php echo $post->design_url; ?>" target="_blank"><?php echo $post->design_credit; ?></a></p>
			<p><strong>LINK:</strong> <a href="<?php echo $post->url; ?>" target="_blank"><?php echo $post->url; ?></a></p>
			<p><strong>DATE COMPLETED:</strong> <?php echo $post->date_completed; ?></p>
			<?php the_content(); ?>
		</div>
	</div>
<?php get_footer(); ?>