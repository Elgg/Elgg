<?php

	/**
	 * A simlpe group search by tag view
	 **/
	 
?>
<div class="sidebarBox">
<h3><?php echo elgg_echo('groups:searchtag'); ?></h3>
<form id="groupssearchform" action="" method="get">
	<input type="text" name="group_find" value="Search" onclick="if (this.value=='Search') { this.value='' }" class="search_input" />
	<input type="submit" value="<?php echo elgg_echo('go'); ?>" />
</form>
</div>
<div class="sidebarBox">
<?php
	if(isloggedin())
		echo "<p><a href=\"{$vars['url']}pg/groups/member/{$_SESSION['user']->username}\">". elgg_echo('groups:yours') ."</a></p>";
		
?>
</div>