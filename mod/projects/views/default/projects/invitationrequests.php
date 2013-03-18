<?php
/**
 * A user's project invitations
 *
 * @uses $vars['invitations'] Array of ElggGroups
 */

if (!empty($vars['invitations']) && is_array($vars['invitations'])) {
	$user = elgg_get_logged_in_user_entity();
	echo '<ul class="elgg-list">';
	foreach ($vars['invitations'] as $project) {
		if ($project instanceof ElggGroup) {
			$icon = elgg_view_entity_icon($project, 'tiny', array('use_hover' => 'true'));

			$project_title = elgg_view('output/url', array(
				'href' => $project->getURL(),
				'text' => $project->name,
				'is_trusted' => true,
			));

			$url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/projects/join?user_guid={$user->guid}&project_guid={$project->guid}");
			$accept_button = elgg_view('output/url', array(
				'href' => $url,
				'text' => elgg_echo('accept'),
				'class' => 'elgg-button elgg-button-submit',
				'is_trusted' => true,
			));

			$url = "action/projects/killinvitation?user_guid={$user->getGUID()}&project_guid={$project->getGUID()}";
			$delete_button = elgg_view('output/confirmlink', array(
					'href' => $url,
					'confirm' => elgg_echo('projects:invite:remove:check'),
					'text' => elgg_echo('delete'),
					'class' => 'elgg-button elgg-button-delete mlm',
			));

			$body = <<<HTML
<h4>$project_title</h4>
<p class="elgg-subtext">$project->briefdescription</p>
HTML;
			$alt = $accept_button . $delete_button;

			echo '<li class="pvs">';
			echo elgg_view_image_block($icon, $body, array('image_alt' => $alt));
			echo '</li>';
		}
	}
	echo '</ul>';
} else {
		echo '<p class="mtm">' . elgg_echo('projects:invitations:none') . "</p>";
}
