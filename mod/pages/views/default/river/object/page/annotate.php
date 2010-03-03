<?php

	$statement = $vars['statement'];
	$performed_by = $statement->getSubject();
	$object = $statement->getObject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("pages:river:posted"),$url) . " ";
	$string .= "<a href=\"" . $object->getURL() . "\">" . elgg_echo("pages:river:annotate:create") . "</a> " . $object->title;
	//$string .= "<div class=\"river_content\">" . $object->title . "</div>";

?>

<?php echo $string; ?>