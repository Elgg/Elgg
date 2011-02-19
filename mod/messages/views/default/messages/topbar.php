<?php
/**
 * Elgg messages topbar extender
 * 
 * @package ElggMessages
 */

if (!elgg_is_logged_in()) {
	return true;
}

// get unread messages
$num_messages = (int)messages_count_unread();

$class = "elgg-icon messages-icon";
$text = "&nbsp;";
if ($num_messages != 0) {
	$class = "$class new";
	$text = $num_messages;
}
$text = "<span class='$class'>$text</span>";

echo elgg_view('output/url', array(
	'href' => 'pg/messages/inbox/' . elgg_get_logged_in_user_entity()->username,
	'text' => $text,
));
