<?php
/**
 * Image upload view
 *
 * @uses $vars['entity']
 */

$entity_image = elgg_view('output/img', array(
	'src' => $vars['entity']->getIconUrl('medium'),
	'alt' => elgg_echo('image'),
));

$current_label = elgg_echo('image:current');

$remove_button = '';
if ($vars['entity']->icontime) {
	$remove_button = elgg_view('output/url', array(
		'text' => elgg_echo('remove'),
		'title' => elgg_echo('image:remove'),
		'href' => "action/image/remove?guid={$vars['entity']->$guid}",
		'is_action' => true,
		'class' => 'elgg-button elgg-button-delete mll',
	));
}

$form_params = array('enctype' => 'multipart/form-data');
$upload_form = elgg_view_form('image/upload', $form_params, $vars);

?>

<p class="mtm">
	<?php echo elgg_echo('image:upload:instructions'); ?>
</p>

<?php

$image = <<<HTML
	<div id="current-entity-image" class="mrl prl">
		<label>$current_label</label><br />
		$entity_image
	</div>
	$remove_button
HTML;

$body = <<<HTML
	<div id="image-upload">
		$upload_form
	</div>
HTML;

echo elgg_view_image_block($image, $upload_form);
