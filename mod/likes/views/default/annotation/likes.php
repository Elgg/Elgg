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

$user = $like->getOwnerEntity();
if (!$user) {
	return true;
}

$user_icon = elgg_view_entity_icon($user, 'tiny');
$user_link = elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => $user->name,
	'is_trusted' => true,
));

$likes_string = elgg_echo('likes:this');

$friendlytime = elgg_view_friendly_time($like->time_created);

$delete_button = '';
if ($like->canEdit()) {
	$delete_button = elgg_view("output/confirmlink",array(
    	'href' => "action/likes/delete?id={$like->id}",
    	'text' => elgg_view_icon('delete', 'float-alt'),
    	'confirm' => elgg_echo('likes:delete:confirm'),
    	'encode_text' => false,
    ));
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
