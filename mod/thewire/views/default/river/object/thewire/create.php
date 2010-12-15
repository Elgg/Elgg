<?php

$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();

$string = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}:</a> ";
$desc = $object->description;
//$desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . elgg_get_site_url() . 'pg/thewire/$1">$1</a>',$desc);
$string .= parse_urls($desc);
$string .= " <span class='entity-subtext'>" . elgg_view_friendly_time($object->time_created);
	if (isloggedin()){
		$string .= "<a class='river_comment_form_button link'>Comment</a>";
		$string .= elgg_view('forms/likes/link', array('entity' => $object));
	}
$string .= "</span>";
echo $string;