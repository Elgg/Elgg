<?php
/**
 * Elgg Message board
 * This plugin allows users and groups to attach a message board to their profile for other users
 * to post comments.
 */

/**
 * Add messageboard post
 *
 * @param ElggUser $poster    User posting the message
 * @param ElggUser $owner     User who owns the message board
 * @param string   $message   The posted message
 * @param int      $access_id Access level (see defines in constants.php)
 *
 * @return false|int
 */
function messageboard_add($poster, $owner, $message, $access_id = ACCESS_PUBLIC) {
	
	if (!$poster instanceof ElggUser || !$owner instanceof ElggUser || empty($message)) {
		return false;
	}
	
	$access_id = (int) $access_id;
	
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

	// Send notification only if poster isn't the owner
	if ($poster->guid != $owner->guid) {
		$subject = elgg_echo('messageboard:email:subject', [], $owner->language);
		$url = elgg_normalize_url("messageboard/owner/{$owner->username}");

		$body = elgg_echo('messageboard:email:body', [
			$poster->getDisplayName(),
			$message,
			$url,
			$poster->getDisplayName(),
			$poster->getURL(),
		], $owner->language);

		$params = [
			'action' => 'create',
			'object' => elgg_get_annotation_from_id($result_id),
			'url' => $url,
		];
		notify_user($owner->guid, $poster->guid, $subject, $body, $params);
	}

	return $result_id;
}
