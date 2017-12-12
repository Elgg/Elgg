<?php

/**
 * Displays HTML with human readable representation of an access level
 *
 * @uses ElggEntity $vars['entity'] Optional. The entity whose access ID to display. If provided, additional logic is used to determine CSS classes
 * @uses int        $vars['value']  Optional. Access ID to display.
 */
$class = elgg_extract_class($vars, 'elgg-access');

$access_id = elgg_extract('value', $vars);

$entity = elgg_extract('entity', $vars);
if ($entity instanceof ElggEntity) {
	$access_id = $entity->access_id;
}

if (!isset($access_id)) {
	return;
}

$access_id_string = get_readable_access_level($access_id);

switch ($access_id) {
	case ACCESS_PUBLIC :
		$class[] = 'elgg-access-public';
		break;

	case ACCESS_LOGGED_IN :
		$class[] = 'elgg-access-loggedin';
		break;

	case ACCESS_PRIVATE :
		$class[] = 'elgg-access-private';
		break;
	
	default:
		$collection = get_access_collection($access_id);
		$owner = false;
		if ($collection) {
			$owner = get_entity($collection->owner_guid);
		}
		if ($owner instanceof ElggGroup) {
			$class[] = 'elgg-access-group';
			$membership = $owner->membership;
			if ($membership == ACCESS_PUBLIC) {
				$class[] = 'elgg-access-group-open';
			} else {
				$class[] = 'elgg-access-group-closed';
			}
		} else {
			if ($collection && !empty($collection->getSubtype())) {
				$class[] = 'elgg-access-' . elgg_get_friendly_title($collection->getSubtype());
			} else {
				$class[] = 'elgg-access-limited';
			}
		}
		break;
}

echo elgg_format_element([
	'#tag_name' => 'span',
	'title' => elgg_echo('access:help'),
	'class' => $class,
	'#text' => $access_id_string,
	'#options' => [
		'encode_text' => true,
	],
]);
