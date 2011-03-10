<?php
/**
 * Content header action buttons
 *
 * @uses $vars['entity']
 * 
 * @todo This should be done by registering menu items with the page actions menu
 */

if (!elgg_is_logged_in()) {
	return true;
}


$actions = array();

// group owners
if ($vars['entity']->canEdit()) {
	// edit and invite
	$url = elgg_get_site_url() . "groups/edit/{$vars['entity']->getGUID()}";
	$actions[$url] = elgg_echo('groups:edit');
	$url = elgg_get_site_url() . "groups/invite/{$vars['entity']->getGUID()}";
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
	$url = elgg_get_site_url() . "action/groups/join?group_guid={$vars['entity']->getGUID()}";
	$url = elgg_add_action_tokens_to_url($url);
	if ($vars['entity']->isPublicMembership() || $vars['entity']->canEdit()) {
		$actions[$url] = elgg_echo('groups:join');
	} else {
		// request membership
		$actions[$url] = elgg_echo('groups:joinrequest');
	}
}

// display action buttons
if ($actions) {
	echo '<ul class="elgg-menu elgg-menu-title elgg-menu-hz">';
	foreach ($actions as $url => $action) {
		echo '<li>';
		echo elgg_view('output/url', array(
			'text' => $action,
			'href' => $url,
			'class' => 'elgg-button elgg-button-action',
		));
		echo '</li>';
	}
	echo '</ul>';
}
