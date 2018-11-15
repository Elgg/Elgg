<?php
/**
 * A group's member requests
 *
 * @uses $vars['entity']   ElggGroup
 * @uses $vars['requests'] Array of ElggUsers
 */

$entity = elgg_extract('entity', $vars);
$requests = elgg_extract('requests', $vars);
if (empty($requests) || !is_array($requests)) {
	echo '<p class="mtm">' . elgg_echo('groups:requests:none') . '</p>';
	return;
}

echo '<ul class="elgg-list">';
foreach ($requests as $user) {
	$icon = elgg_view_entity_icon($user, 'small', ['use_hover' => 'true']);

	$user_title = elgg_view('output/url', [
		'href' => $user->getURL(),
		'text' => $user->getDisplayName(),
		'is_trusted' => true,
	]);

	$accept_button = elgg_view('output/url', [
		'href' => elgg_generate_action_url('groups/addtogroup', [
			'user_guid' => $user->guid,
			'group_guid' => $entity->guid,
		]),
		'text' => elgg_echo('accept'),
		'class' => 'elgg-button elgg-button-submit',
	]);

	$delete_button = elgg_view('output/url', [
		'href' => elgg_generate_action_url('groups/killrequest', [
			'user_guid' => $user->guid,
			'group_guid' => $entity->guid,
		]),
		'confirm' => elgg_echo('groups:joinrequest:remove:check'),
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete mlm',
	]);

	$body = "<h4>$user_title</h4>";

	echo '<li class="pvs">';
	echo elgg_view_image_block($icon, $body, ['image_alt' => $accept_button . $delete_button]);
	echo '</li>';
}
echo '</ul>';
