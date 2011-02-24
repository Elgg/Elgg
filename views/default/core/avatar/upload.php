<?php
/**
 * Avatar upload view
 *
 * @uses $vars['entity']
 */

$user_avatar = $vars['entity']->getIcon('medium');

$current_label = elgg_echo('avatar:current');

$form_params = array('enctype' => 'multipart/form-data');
$upload_form = elgg_view_form('avatar/upload', $form_params, $vars);

?>

<p class="mtm">
	<?php echo elgg_echo('avatar:upload:instructions'); ?>
</p>

<?php

$image = <<<HTML
<div id="current-user-avatar" class="mrl prl">
	<label>$current_label</label><br />
	<img src="$user_avatar" alt="avatar" />
</div>
HTML;

$body = <<<HTML
<div id="avatar-upload">
	$upload_form
</div>
HTML;

echo elgg_view_image_block($image, $upload_form);
