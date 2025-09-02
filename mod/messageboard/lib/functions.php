<?php
/**
 * Holds helper functions for messageboard plugin
 */

/**
 * Add messageboard post
 *
 * @param \ElggUser $poster    User posting the message
 * @param \ElggUser $owner     User who owns the message board
 * @param string    $message   The posted message
 * @param int       $access_id Access level (see defines in constants.php)
 *
 * @return false|int
 * @deprecated 6.3
 */
function messageboard_add(\ElggUser $poster, \ElggUser $owner, string $message, int $access_id = ACCESS_PUBLIC): int|false {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated and will be removed', '6.3');
	
	if (empty($message)) {
		return false;
	}
	
	$result_id = $owner->annotate('messageboard', $message, $access_id, $poster->guid);
	if (!is_int($result_id)) {
		return false;
	}

	elgg_create_river_item([
		'view' => 'river/object/messageboard/create',
		'action_type' => 'messageboard',
		'subject_guid' => $poster->guid,
		'object_guid' => $owner->guid,
		'access_id' => $access_id,
		'annotation_id' => $result_id,
	]);

	return $result_id;
}
