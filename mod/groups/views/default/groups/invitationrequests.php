<?php
/**
 * A user's group invitations
 *
 * @uses $vars['invitations'] Array of ElggGroups
 */

if (!empty($vars['invitations']) && is_array($vars['invitations'])) {
	$user = elgg_get_logged_in_user_entity();
	echo '<ul class="elgg-list">';
	foreach ($vars['invitations'] as $group) {
		if ($group instanceof ElggGroup) {
			$icon = elgg_view_entity_icon($group, 'tiny', array('override' => 'true'));

			$group_title = elgg_view('output/url', array(
				'href' => $group->getURL(),
				'text' => $group->name,
			));

			$url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}");
			$accept_button = elgg_view('output/url', array(
				'href' => $url,
				'text' => elgg_echo('accept'),
				'class' => 'elgg-button elgg-button-submit',
			));

			$url = "action/groups/killinvitation?user_guid={$user->getGUID()}&group_guid={$group->getGUID()}";
			$delete_button = elgg_view('output/confirmlink', array(
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
} else {
		echo '<p class="mtm">' . elgg_echo('groups:invitations:none') . "</p>";
}
