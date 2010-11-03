<?php

	$statement = $vars['statement'];
	$performed_by = $statement->getSubject();
	$object = $statement->getObject();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = elgg_echo("groupforum:river:created", array($url)) . " ";
	$string .= elgg_echo("groupforum:river:create") . " | <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
	//$string .= "<div class=\"river_content\">Discussion topic: " . $object->title . "</div>";

?>

<?php echo $string; ?>