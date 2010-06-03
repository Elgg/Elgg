<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();

	$string = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}:</a> ";
	$desc = $object->description;
	//$desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'pg/thewire/$1">$1</a>',$desc);
	$string .= parse_urls($desc);

	$string .= " <span class='entity_subtext'>" . friendly_time($object->time_created) . "</span> <a href=\"{$vars['url']}mod/thewire/add.php?wire_username={$object->getOwnerEntity()->username}\" class='reply_link'>" . elgg_echo('thewire:reply') . "</a>";
?>

<?php 
	echo $string; 
?>