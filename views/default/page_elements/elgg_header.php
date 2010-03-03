<?php
/**
 * Elgg header contents
 * This file holds the header output that a user will see
 **/

?>
<div id="elgg_header">
	<div id="elgg_header_contents">
		<!-- display site name -->
		<h1><a href="<?php echo $vars['url']; ?>"><?php echo $vars['config']->sitename; ?></a></h1>
		<?php
			// insert a view which can be extended
			echo elgg_view('header/extend');
		?>
		
		<div id="elgg_search">
			<form id="searchform" action="<?php echo $vars['url']; ?>pg/search/" method="get">
				<input type="text" size="21" name="tag" value="<?php echo elgg_echo('search'); ?>" onblur="if (this.value=='') { this.value='<?php echo elgg_echo('search'); ?>' }" onfocus="if (this.value=='<?php echo elgg_echo('search'); ?>') { this.value='' };" class="search_input" />
				<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search_submit_button" />
			</form>
		</div>

	</div>
</div>