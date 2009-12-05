<?php
/**
 * Elgg comment river view 
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();
$title = $object->title;
if (!$title) {
	$title = elgg_echo('untitled');
}
$subtype = get_subtype_from_id($object->subtype);

//grab the annotation, if one exists
if ($vars['item']->annotation_id != 0) {
	$comment = get_annotation($vars['item']->annotation_id)->value;
}
$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = sprintf(elgg_echo("river:posted:generic"),$url) . " ";
$string .= elgg_echo("{$subtype}:river:annotate") . " | <a href=\"{$object->getURL()}\">" . $title . "</a>";
$string .= "<div class=\"river_content_display\">";

if ($comment) {
	$contents = strip_tags($comment);//this is so we don't get large images etc in the activity river
	if (strlen($contents) > 200) {
		$string .= substr($contents, 0, strpos($contents, ' ', 200)) . "...";
	} else {
		$string .= $contents;
	}
}

$string .= "</div>";

echo $string;