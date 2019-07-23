<?php
/**
 * Add cropping features to icon uploading
 *
 * @uses $vars['entity']                    the entity being edited
 * @uses $vars['entity_type']               the type of the entity
 * @uses $vars['entity_subtype']            the subtype of the entity
 * @uses $vars['cropper_enabled']           enable cropper features (default: false)
 * @uses $vars['cropper_config']            configuration for CropperJS
 * @uses $vars['cropper_aspect_ratio_size'] the icon size to use to detect cropping aspact ratio (default: master) pass 'false' to disable
 * @uses $vars['cropper_show_messages']     show messages (default: true for icon_type = 'icon', false otherwise)
 * @uses $vars['cropper_min_width']         the minimal width of the cropped image
 * @uses $vars['cropper_min_height']        the minimal height of the cropped image
 */

if (elgg_extract('cropper_enabled', $vars, false) === false) {
	return;
}

elgg_require_css('cropperjs/cropper');

$entity = elgg_extract('entity', $vars);
$icon_type = elgg_extract('icon_type', $vars, 'icon');
$input_name = elgg_extract('name', $vars, 'icon');

// build cropper configuration
$default_config = [
	'viewMode' => 2,
	'background' => false,
	'autoCropArea' => 1,
];

$cropper_data = array_merge($default_config, (array) elgg_extract('cropper_config', $vars, []));

// determin current cropping coordinates
$entity_coords = [];
if ($entity instanceof ElggEntity) {
	if ($icon_type === 'icon') {
		$entity_coords = [
			'x1' => $entity->x1,
			'y1' => $entity->y1,
			'x2' => $entity->x2,
			'y2' => $entity->y2,
		];
	} elseif (isset($entity->{"{$icon_type}_coords"})) {
		$entity_coords = unserialize($entity->{"{$icon_type}_coords"});
	}
	
	// cast to ints
	array_walk($entity_coords, function(&$value) {
		$value = (int) $value;
	});
	// remove invalid values
	$entity_coords = array_filter($entity_coords, function($value) {
		return $value >= 0;
	});
	
	// still enough for cropping
	if (isset($entity_coords['x1'], $entity_coords['x2'], $entity_coords['y1'], $entity_coords['y2'])) {
		$cropper_data['data'] = [
			'x' => $entity_coords['x1'],
			'y' => $entity_coords['y1'],
			'width' => $entity_coords['x2'] - $entity_coords['x1'],
			'height' => $entity_coords['y2'] - $entity_coords['y1'],
		];
	}
}

// detect cropping aspect ratio
if (!isset($cropper_data['aspectRatio'])) {
	$detect_aspect_ratio = function($vars) use (&$cropper_data) {
		$cropper_aspect_ratio_size = elgg_extract('cropper_aspect_ratio_size', $vars, 'master');
		if ($cropper_aspect_ratio_size === false) {
			return;
		}
		
		$icon_type = elgg_extract('icon_type', $vars, 'icon');
		$entity_type = elgg_extract('entity_type', $vars);
		$entity_subtype = elgg_extract('entity_subtype', $vars);
		
		$sizes = elgg_get_icon_sizes($entity_type, $entity_subtype, $icon_type);
		if (empty($sizes)) {
			// no way to read the config
			return;
		}
		
		if (!isset($sizes[$cropper_aspect_ratio_size]) && $cropper_aspect_ratio_size !== 'master') {
			// fallback to master if custom ratio is missing
			$cropper_aspect_ratio_size = 'master';
		}
		
		if (!isset($sizes[$cropper_aspect_ratio_size])) {
			// return if ratio is not present
			return;
		}
		
		$width = (int) elgg_extract('w', $sizes[$cropper_aspect_ratio_size]);
		$height = (int) elgg_extract('h', $sizes[$cropper_aspect_ratio_size]);
		
		if (empty($width) || empty($height)) {
			return;
		}
		
		$cropper_data['aspectRatio'] = $width / $height;
	};
	$detect_aspect_ratio($vars);
}

$img_url = null;
if ($entity instanceof ElggEntity && $entity->hasIcon('master', $icon_type)) {
	$img_url = $entity->getIconURL([
		'size' => 'master',
		'type' => $icon_type,
	]);
}

$img = elgg_format_element('img', [
	'data-icon-cropper' => json_encode($cropper_data),
	'src' => $img_url,
]);

echo elgg_format_element('div', ['class' => ['elgg-entity-edit-icon-crop-wrapper', 'hidden', 'mbm']], $img);

$input ='';
foreach (['x1', 'y1', 'x2', 'y2'] as $coord) {
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => $coord,
		'value' => elgg_extract($coord, $entity_coords),
	]);
}

if (!empty($img_url)) {
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => '_entity_edit_icon_crop_guid',
		'value' => $entity->guid,
	]);
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => '_entity_edit_icon_crop_type',
		'value' => $icon_type,
	]);
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => '_entity_edit_icon_crop_input',
		'value' => $input_name,
	]);
}

echo elgg_format_element('div', ['class' => ['elgg-entity-edit-icon-crop-input', 'hidden']], $input);

echo elgg_view('entity/edit/icon/crop_messages', $vars);

?>
<script>
	require(['entity/edit/icon/crop'], function(Cropper) {
		var cropper = new Cropper();
		
		cropper.init('input[type="file"][name="<?php echo elgg_extract('name', $vars, 'icon'); ?>"]');
	});
</script>
