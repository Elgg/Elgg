<?php
/**
 * Avatar upload view
 *
 * @uses $vars['entity']
 */

$user_avatar = elgg_view('output/img', [
	'src' => $vars['entity']->getIconUrl('medium'),
	'alt' => elgg_echo('avatar'),
]);

$current_label = elgg_echo('avatar:current');

$remove_button = '';
if ($vars['entity']->icontime) {
	$remove_button = elgg_view('output/url', [
		'text' => elgg_echo('remove'),
		'title' => elgg_echo('avatar:remove'),
		'href' => elgg_generate_action_url('avatar/remove', [
			'guid' => elgg_get_page_owner_guid(),
		]),
		'class' => 'elgg-button elgg-button-cancel',
	]);
}

$form_params = ['enctype' => 'multipart/form-data'];
$upload_form = elgg_view_form('avatar/upload', $form_params, $vars);

?>

<p class="mtm">
	<?php echo elgg_echo('avatar:upload:instructions'); ?>
</p>

<?php

$image = <<<HTML
<div id="current-user-avatar" class="elgg-justify-center pam">
	<label>$current_label</label><br />
	<div>$user_avatar</div>
	<div>$remove_button</div>
</div>
HTML;

$body = <<<HTML
<div id="avatar-upload" class="pam">
	$upload_form
</div>
HTML;

echo elgg_view_image_block($image, $body);
