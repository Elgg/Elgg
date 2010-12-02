<?php

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();
$title = $object->title;
 
$string = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a> ";
$string .= elgg_echo("likes:river:annotate") . "  <a href=\"{$object->getURL()}\">" . $title . "</a> <span class='entity-subtext'>" . elgg_view_friendly_time($object->time_created)."</span>";
	
echo $string;