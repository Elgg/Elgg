<?php
/**
 * A group's member requests
 *
 * @uses $vars['entity']   ElggGroup
 * @uses $vars['requests'] Array of ElggUsers
 */

if (!empty($vars['requests']) && is_array($vars['requests'])) {
	echo '<ul class="elgg-list">';
	foreach ($vars['requests'] as $user) {
		$icon = elgg_view_entity_icon($user, 'tiny', array('use_hover' => 'true'));

		$user_title = elgg_view('output/url', array(
			'href' => $user->getURL(),
			'text' => $user->name,
			'is_trusted' => true,
		));

		$url = "action/groups/addtogroup?user_guid={$user->guid}&group_guid={$vars['entity']->guid}";
		$url = elgg_add_action_tokens_to_url($url);
		$accept_button = elgg_view('output/url', array(
			'href' => $url,
			'text' => elgg_echo('accept'),
			'class' => 'elgg-button elgg-button-submit',
			'is_trusted' => true,
		));

		$url = 'action/groups/killrequest?user_guid=' . $user->guid . '&group_guid=' . $vars['entity']->guid;
		$delete_button = elgg_view('output/confirmlink', array(
				'href' => $url,
				'confirm' => elgg_echo('groups:joinrequest:remove:check'),
				'text' => elgg_echo('delete'),
				'class' => 'elgg-button elgg-button-delete mlm',
		));

		$body = "<h4>$user_title</h4>";
		$alt = $accept_button . $delete_button;

		echo '<li class="pvs">';
		echo elgg_view_image_block($icon, $body, array('image_alt' => $alt));
		echo '</li>';
	}
	echo '</ul>';
} else {
	echo '<p class="mtm">' . elgg_echo('groups:requests:none') . '</p>';
}
