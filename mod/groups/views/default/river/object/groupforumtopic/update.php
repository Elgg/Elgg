<?php

	$statement = $vars['statement'];
	$performed_by = $statement->getSubject();
	$object = $statement->getObject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("groupforum:river:updated"),$url) . " ";
    $string .= elgg_echo("groupforum:river:update") . " | <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
    
?>

<?php echo $string; ?>