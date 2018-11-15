<?php
/**
 * Elgg show the users who liked the object
 *
 * @uses $vars['annotation']
 */

if (!isset($vars['annotation'])) {
	return true;
}

$like = $vars['annotation'];
/* @var ElggAnnotation $like */

$user = $like->getOwnerEntity();
if (!$user) {
	return true;
}

$user_icon = elgg_view_entity_icon($user, 'tiny', ['use_hover' => false]);
$user_link = elgg_view('output/url', [
	'href' => $user->getURL(),
	'text' => $user->getDisplayName(),
	'is_trusted' => true,
]);

$likes_string = elgg_echo('likes:this');

$friendlytime = elgg_view_friendly_time($like->time_created);

$delete_button = '';
if ($like->canEdit()) {
	$delete_button = elgg_view("output/url", [
		'href' => elgg_generate_action_url('likes/delete', [
			'id' => $like->id,
		]),
		'text' => elgg_view_icon('delete', 'float-alt'),
		'confirm' => elgg_echo('likes:delete:confirm'),
		'encode_text' => false,
	]);
}

$body = <<<HTML
<p class="mbn">
	$delete_button
	$user_link $likes_string
	<span class="elgg-subtext">
		$friendlytime
	</span>
</p>
HTML;

echo elgg_view_image_block($user_icon, $body);
