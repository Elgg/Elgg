<?php
/**
 * Show an example of the current icon
 *
 * @uses $vars['entity']          entity to edit
 * @uses $vars['icon_type']       the type of the icon (default: icon)
 * @uses $vars['show_thumb']      show the thumb of the entity if available (default: !cropper_enabled)
 * @uses $vars['thumb_size']      the icon size to use as the thumb (default: medium)
 * @uses $vars['cropper_enabled'] enable cropper features (default: false), when cropper is enabled thumbnail is disabled by default
 */

if (elgg_extract('show_thumb', $vars, !elgg_extract('cropper_enabled', $vars, false)) === false) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	// no way to check for icon
	return;
}

$icon_type = elgg_extract('icon_type', $vars, 'icon');
$size = elgg_extract('thumb_size', $vars, 'medium');

if (!$entity->hasIcon($size, $icon_type)) {
	// no icon uploaded
	return;
}

echo elgg_format_element('div', ['class' => ['elgg-entity-icon-edit-thumb']], elgg_view('output/img', [
	'src' => $entity->getIconURL([
		'size' => $size,
		'type' => $icon_type,
	]),
	'alt' => "{$icon_type}-{$size}",
	'title' => $entity->getDisplayName(),
]));
