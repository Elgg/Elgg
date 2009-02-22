<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$objecturl = $object->getURL();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("groups:river:member"),$url) . " ";
	$string .= " <a href=\"" . $object->getURL() . "\">" . $object->name . "</a>";
	
?>

<?php echo $string; ?>