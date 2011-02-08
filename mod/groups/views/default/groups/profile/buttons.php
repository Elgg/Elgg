<?php
/**
 * Content header action buttons
 *
 * @uses $vars['entity']
 */

if (!elgg_is_logged_in()) {
	return true;
}


$actions = array();

// group owners
if ($vars['entity']->canEdit()) {
	// edit and invite
	$url = elgg_get_site_url() . "pg/groups/edit/{$vars['entity']->getGUID()}";
	$actions[$url] = elgg_echo('groups:edit');
	$url = elgg_get_site_url() . "pg/groups/invite/{$vars['entity']->getGUID()}";
	$actions[$url] = elgg_echo('groups:invite');
}

// group members
if ($vars['entity']->isMember($user)) {
	// leave
	$url = elgg_get_site_url() . "action/groups/leave?group_guid={$vars['entity']->getGUID()}";
	$url = elgg_add_action_tokens_to_url($url);
	$actions[$url] = elgg_echo('groups:leave');
} else {
	// join - admins can always join.
	if ($vars['entity']->isPublicMembership() || $vars['entity']->canEdit()) {
		$url = elgg_get_site_url() . "action/groups/join?group_guid={$vars['entity']->getGUID()}";
		$url = elgg_add_action_tokens_to_url($url);
		$actions[$url] = elgg_echo('groups:join');
	} else {
		// request membership
		$url = elgg_get_site_url() . "action/groups/joinrequest?group_guid={$vars['entity']->getGUID()}";
		$url = elgg_add_action_tokens_to_url($url);
		$actions[$url] = elgg_echo('groups:joinrequest');
	}
}

// display action buttons
if ($actions) {
	foreach ($actions as $url => $action) {
		echo elgg_view('output/url', array(
			'text' => $action,
			'href' => $url,
			'class' => 'elgg-action-button',
		));
	}
}
