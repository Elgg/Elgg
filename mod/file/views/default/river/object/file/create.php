<?php

	$performed_by = get_entity($vars['item']->subject_guid);
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	$container = get_entity($object->container_guid);
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("file:river:created"),$url) . " " . elgg_echo("file:river:item");
	$string .= " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
	if ($container && $container instanceof ElggGroup) {
		$string .= ' ' . elgg_echo('groups:river:togroup') . " <a href=\"" . $container->getURL() ."\">". $container->name . "</a>";
	}

	echo $string;
	
?>