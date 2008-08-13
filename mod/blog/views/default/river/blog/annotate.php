<?php

	$statement = $vars['statement'];
	$performed_by = $statement->getSubject();
	$object = $statement->getObject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("blog:river:posted"),$url) . " ";
	$string .= "<a href=\"" . $object->getURL() . "\">" . elgg_echo("blog:river:annotate:create") . "</a>";

?>

<?php echo $string; ?>