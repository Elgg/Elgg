<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();

	$string = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}:</a> ";
	$string .= $object->description;

	$string .= " (<a href=\"{$vars['url']}mod/thewire/add.php?wire_username={$object->getOwnerEntity()->username}\" class=\"reply\">" . elgg_echo('thewire:reply') . "</a>)";
?>

<?php 
	echo $string; 
?>