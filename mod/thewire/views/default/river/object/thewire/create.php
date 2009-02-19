<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("thewire:river:created"),$url) . " ";
	$string .= "\"" . $object->description . "\" " . elgg_echo("thewire:river:create");

?>

<?php 
	echo $string; 
?>