<?php
/**
 * A user's group invitations
 *
 * @uses $vars['invitations'] Array of ElggGroups
 */

$user = elgg_get_page_owner_entity();
if (!elgg_instanceof($user, 'user') || !$user->canEdit()) {
	return true;
}

if (isset($vars['invitations'])) {
	$invitations = $vars['invitations'];
} else {
	$limit = get_input('limit', elgg_get_config('default_limit'));
	$offset = get_input('offset', 0);
	$count = groups_get_invited_groups($user->guid, false, array('count' => true));
	$invitations = groups_get_invited_groups($user->guid, false, array(
		'limit' => $limit,
		'offset' => $offset
			));
}

if (is_array($invitations) && count($invitations) > 0) {
	$user = elgg_get_logged_in_user_entity();
	echo '<ul class="elgg-list">';
	foreach ($invitations as $group) {
		if ($group instanceof ElggGroup) {
			$icon = elgg_view_entity_icon($group, 'tiny', array('use_hover' => 'true'));

			$group_title = elgg_view('output/url', array(
				'href' => $group->getURL(),
				'text' => $group->name,
				'is_trusted' => true,
			));

			$url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}");
			$accept_button = elgg_view('output/url', array(
				'href' => $url,
				'text' => elgg_echo('accept'),
				'class' => 'elgg-button elgg-button-submit',
				'is_trusted' => true,
			));

			$url = "action/groups/killinvitation?user_guid={$user->getGUID()}&group_guid={$group->getGUID()}";
			$delete_button = elgg_view('output/url', array(
					'href' => $url,
					'confirm' => elgg_echo('groups:invite:remove:check'),
					'text' => elgg_echo('delete'),
					'class' => 'elgg-button elgg-button-delete mlm',
			));

			$body = <<<HTML
<h4>$group_title</h4>
<p class="elgg-subtext">$group->briefdescription</p>
HTML;
			$alt = $accept_button . $delete_button;

			echo '<li class="pvs">';
			echo elgg_view_image_block($icon, $body, array('image_alt' => $alt));
			echo '</li>';
		}
	}
	echo '</ul>';

	if (!empty($count)) {
		echo elgg_view('navigation/pagination', array(
			'count' => $count,
			'limit' => $limit,
			'offset' => $offset,
		));
	}
} else {
		echo '<p class="mtm">' . elgg_echo('groups:invitations:none') . "</p>";
}
