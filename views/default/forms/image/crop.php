<?php
/**
 * Image crop form
 *
 * @uses $vars['entity']
 */

elgg_load_js('jquery.imgareaselect');
elgg_require_js('elgg/image_cropper');
elgg_load_css('jquery.imgareaselect');

$master_img = elgg_view('output/img', array(
	'src' => $vars['entity']->getIconUrl('master'),
	'alt' => elgg_echo('image'),
	'class' => 'mrl',
	'id' => 'entity-image-cropper',
));

$preview_img = elgg_view('output/img', array(
	'src' => $vars['entity']->getIconUrl('master'),
	'alt' => elgg_echo('image'),
));

?>
<div class="clearfix">
	<?php echo $master_img; ?>
	<div><label><?php echo elgg_echo('image:preview'); ?></label></div>
	<div id="entity-image-preview"><?php echo $preview_img; ?></div>
</div>
<div class="elgg-foot">
<?php

$coords = array('x1', 'x2', 'y1', 'y2');
foreach ($coords as $coord) {
	echo elgg_view('input/hidden', array(
		'name' => $coord,
		'value' => $vars['entity']->$coord,
	));
}

echo elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['entity']->guid,
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo('image:create')
));

?>
</div>
