<?php

	/**
	 * A simlpe group search by tag view
	 **/
	 
?>
<div class="sidebarBox">
<h3><?php echo elgg_echo('groups:searchtag'); ?></h3>
<form id="groupsearchform" action="<?php echo $vars['url']; ?>search/" method="get">
	<input type="text" name="tag" value="tag" onclick="if (this.value=='tag') { this.value='' }" class="search_input" />
	<input type="hidden" name="subtype" value="" />
	<input type="hidden" name="object" value="group" />
	<input type="hidden" name="tagtype" value="" />
	<input type="hidden" name="owner_guid" value="0" />
	<input type="submit" value="<?php echo elgg_echo('go'); ?>" />
</form>
</div>