<?php

/**
 * New wire post view for the activity stream
 */

$owner = $vars['entity']->guid;
$url_to_wire = $vars['url'] . "pg/thewire/owner/" . $vars['entity']->username;

//grab the user's latest from the wire
$params = array(
	'types' => 'object',
	'subtypes' => 'thewire',
	'owner_guid' => $owner,
	'limit' => 1,
);
$latest_wire = elgg_get_entities($params);

if ($latest_wire) {
	foreach ($latest_wire as $lw) {
		$content = $lw->description;
		$time = "<span> (" . elgg_view_friendly_time($lw->time_created) . ")</span>";
	}
}

if ($latest_wire) {
	echo "<div class=\"profile_status\">";
	echo $content;
	if ($owner == get_loggedin_userid()) {
		$text = elgg_echo('thewire:update');
		echo " <a class=\"status_update\" href=\"$url_to_wire\">$text</a>";
	}
	echo $time;
	echo "</div>";
}