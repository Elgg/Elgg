<form id="searchform" action="<?php echo $vars['url']; ?>pg/search/" method="get">
	<input type="text" size="21" name="tag" value="<?php echo elgg_echo('search'); ?>" onclick="if (this.value=='<?php echo elgg_echo('search'); ?>') { this.value='' }" class="search_input" />
	<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search_submit_button" />
</form>
