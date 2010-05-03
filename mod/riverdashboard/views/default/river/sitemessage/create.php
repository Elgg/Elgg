<?php

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = sprintf(elgg_echo("sitemessages:river:created"), $url) . " ";
$string .= elgg_echo("sitemessages:river:create");
$string .= "<div class=\"river_content_display\">";
$string .= $object->description;
$string .= "</div>";

echo $string; 
