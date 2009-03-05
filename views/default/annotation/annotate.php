<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	$subtype = get_subtype_from_id($object->subtype);

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("river:posted:generic"),$url) . " ";
	$string .= elgg_echo("{$subtype}:river:annotate") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";

?>

<?php echo $string; ?>