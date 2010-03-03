<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();

	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("pages:river:updated"),$url) . " ";
	$string .= elgg_echo("pages:river:update") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
    
?>

<?php echo $string; ?>