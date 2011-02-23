<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full', $vars, false);
$message = elgg_extract('entity', $vars, false);

if (!$message) {
	return true;
}

if ($full) {
	$message->readYet = true;
}

if ($message->toId == elgg_get_page_owner_guid()) {
	// received
	$user = get_entity($message->fromId);
	$icon = elgg_view('profile/icon', array('entity' => $user, 'size' => 'tiny'));
	$user_link = elgg_view('output/url', array(
		'href' => "pg/messages/compose?send_to=$user->guid",
		'text' => $user->name,
	));

	if ($message->readYet) {
		$class = 'message read';
	} else {
		$class = 'message unread';
	}

} else {
	// sent
	$user = get_entity($message->toId);
	$icon = elgg_view('profile/icon', array('entity' => $user, 'size' => 'tiny'));
	$user_link = elgg_view('output/url', array(
		'href' => "pg/messages/compose?send_to=$user->guid",
		'text' => elgg_echo('messages:to_user', array($user->name)),
	));

	$class = 'message read';
}

$timestamp = elgg_view_friendly_time($message->time_created);

$subject_info = '';
if (!$full) {
	$subject_info .= "<input type='checkbox' name=\"message_id[]\" value=\"{$message->guid}\" />";
}
$subject_info .= elgg_view('output/url', array(
	'href' => $message->getURL(),
	'text' => $message->title,
));

$delete_link = "<span class='elgg-button elgg-button-delete'>" . elgg_view("output/confirmlink", array(
						'href' => "action/messages/delete?guid=" . $message->getGUID(),
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm'),
					)) . "</span>";

$body = <<<HTML
<div class="messages-owner">$user_link</div>
<div class="messages-subject">$subject_info</div>
<div class="messages-timestamp">$timestamp</div>
<div class="messages-delete">$delete_link</div>
HTML;

if ($full) {
	echo elgg_view_image_block($icon, $body, array('class' => $class));
	echo elgg_view('output/longtext', array('value' => $message->description));
} else {
	echo elgg_view_image_block($icon, $body, array('class' => $class));
}