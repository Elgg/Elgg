<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("bookmarks:river:created"),$url) . " ";
	$string .= "<a href=\"" . $object->getURL() . "\">" . $object->title . "</a>"; //elgg_echo("bookmarks:river:item") . "</a>";
	
?>

<?php 
	echo $string;
?>