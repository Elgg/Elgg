<?php
/**
 * Grabs more comments to display.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

$limit = get_input('limit', 25);
// 3 are displayed by default.
$offset = get_input('offset', 3);
$entity_guid = get_input('entity_guid');
if (!$entity = get_entity($entity_guid)) {
	exit;
}

// same deal as the main view...get the newest $limit, but reverse it to put the newest at the bottom.
if ($comments = get_annotations($entity_guid, "", "", 'generic_comment', "", "", $limit, $offset, "desc")) {
	$comments = array_reverse($comments);
}

foreach ($comments as $comment) {
	//get the comment owner
	$comment_owner = get_user($comment->owner_guid);
	//get the comment owner's profile url
	$comment_owner_url = $comment_owner->getURL();

	//display comment
	echo "<div class='river-comment clearfix'>";
	echo "<span class='river-comment-owner-icon'>";
	echo elgg_view("profile/icon", array('entity' => $comment_owner, 'size' => 'tiny'));
	echo "</span>";

	//truncate comment to 150 characters and strip tags
	$contents = elgg_get_excerpt($comment->value, 150);

	echo "<div class='river-comment-contents'>";
	echo "<a href=\"{$comment_owner_url}\">" . $comment_owner->name . '</a>&nbsp;<span class="twitter_anywhere">' . parse_urls($contents) . '</span>';
	echo "<span class='entity-subtext'>" . elgg_view_friendly_time($comment->time_created) . "</span>";
	echo "</div></div>";
}