<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = elgg_echo("profile:river:update", array($url)) ." <span class='entity-subtext'>" . elgg_view_friendly_time($vars['item']->posted) . "</span>";

?>

<?php echo $string; ?>