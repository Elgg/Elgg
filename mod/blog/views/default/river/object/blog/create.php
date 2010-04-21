<?php
/**
 * Blog river view.
 */

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();
$contents = strip_tags($object->description); //strip tags from the contents to stop large images etc blowing out the river view

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$title = sprintf(elgg_echo('blog:river:create'), $url);
$string .= $title . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a> <span class='entity_subtext'>" . friendly_time($object->publish_time) . "</span>";
if (get_plugin_setting('activitytype', 'riverdashboard') != 'classic'){
	$string .= "<a class='river_comment_form_button link'>Comment</a>";
	$string .= elgg_view('likes/forms/link', array('entity' => $object));
}
$string .= "<div class=\"river_content_display\">";
	$string .= "<div class='river_object_blog_create'></div>";
	if(strlen($contents) > 200) {
        	$string .= substr($contents, 0, strpos($contents, ' ', 200)) . "&hellip;";
    }else{
	    $string .= $contents;
    }
	$string .= "</div>";
echo $string;