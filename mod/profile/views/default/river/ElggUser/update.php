<?php

	$performed_by = $vars['performed_by'];
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("profile:river:update"),$url);

?>

<p class="river_profile_update">
	<?php echo $string; ?>
</p>