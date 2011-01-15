<?php
/**
 * Elgg messages topbar extender
 * 
 * @package ElggMessages
 */

if (!isloggedin()) {
	return true;
}

// get unread messages
$num_messages = (int)messages_count_unread();

$class = "elgg-icon messages-icon";
$text = "&nbsp;";
if ($num_messages != 0) {
	$class = "$class new";
	$text = "<span>$num_messages</span>";
}

echo elgg_view('output/url', array(
	'href' => 'pg/messages/inbox/' . get_loggedin_user()->username,
	'text' => $text,
	'class' => $class,
));
