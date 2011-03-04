<?php
/**
 * A user's group invitations
 *
 * @uses $vars['invitations']
 */

if (!empty($vars['invitations']) && is_array($vars['invitations'])) {
	$user = elgg_get_logged_in_user_entity();
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
					'class' => 'elgg-button elgg-button-action elgg-state-disabled',
			));

			$body = <<<HTML
<p class="entity-title">$group_title</p>
<p class="entity-subtext">$group->briefdescription</p>
$accept_button $delete_button
HTML;
			echo elgg_view_image_block($icon, $body);
		}
	}
} else {
		echo "<p class='default_string mtm'>" . elgg_echo('groups:invitations:none') . "</p>";
}
