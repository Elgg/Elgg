<?php
/**
 * Show a pending/sent friendship request
 *
 * @uses $vars['relationship'] The friendship request relationship
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$current_user = elgg_get_logged_in_user_entity();
if (!empty($current_user)) {
	$friend = false;
	$lan_key = false;
	if ($relationship->guid_one === $current_user->guid) {
		// sent request
		$friend = get_user($relationship->guid_two);
		$lan_key = 'relationship:friendrequest:sent';
	} elseif ($relationship->guid_two === $current_user->guid) {
		// pending approval
		$friend = get_user($relationship->guid_one);
		$lan_key = 'relationship:friendrequest:pending';
	}
	
	if ($friend instanceof ElggUser && !empty($lan_key)) {
		$vars['title'] = elgg_echo($lan_key, [elgg_view_entity_url($friend)]);
	}
}

echo elgg_view('relationship/elements/summary', $vars);
