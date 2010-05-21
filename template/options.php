<div class="wrap">
	<h2>Options</h2>
	<form action="" method="post">
		<?php wp_nonce_field($_REQUEST['page']); ?>
		<input type="hidden" name="action" value="options" />
		<div id="poststuff" class="postbox">
			<h3>Parent Page</h3>
			<div class="inside">
			<?php echo show_pages( $options->parent_page );?>
			</div>
		</div>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save') ?>" />&nbsp;
			<a class="button" href="<?php echo $_SERVER['HTTP_REFERER'];?>">Back</a>
		</p>
		</form>
</div>
