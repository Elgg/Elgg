<?php

/**
 * Displays information about access of the post
 *
 * @uses $vars['entity']      The entity to show the byline for
 * @uses $vars['access']      Access level of the post
 *                            If not set, will display the access level of the entity (access_id attribute)
 *                            If set to false, will not be rendered
 * @uses $vars['access_icon'] Icon name to be used with the access info
 *                            Set to false to not render an icon
 *                            Default is determined by access level ('user', 'globe', 'lock', or 'cog')
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$access = elgg_extract('access', $vars);
if (!isset($access)) {
	$access = $entity->access_id;
}

if ($access === false || !elgg_is_logged_in()) {
	return;
}

switch ($access) {
	case ACCESS_FRIENDS :
		$icon_name = 'user';
		break;
	case ACCESS_PUBLIC :
	case ACCESS_LOGGED_IN :
		$icon_name = 'globe';
		break;
	case ACCESS_PRIVATE :
		$icon_name = 'lock';
		break;
	default:
		$icon_name = 'cog';
		break;
}

$icon_name = elgg_extract('access_icon', $vars, $icon_name);

if ($icon_name === false) {
	$icon = '';
} else {
	$icon = elgg_view_icon($icon_name, [
		'title' => get_readable_access_level($access),
	]);
}

$access_str = $icon . elgg_view('output/access', [
			'value' => $access,
		]);

echo elgg_format_element('span', [
	'class' => 'elgg-listing-access',
		], $access_str);
