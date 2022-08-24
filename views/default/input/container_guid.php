<?php
/**
 * Entity container guid input
 *
 * @uses $vars['name']           Optional. Name of the container guid hidden input
 * @uses $vars['value']          Optional. Current value of the input. Will default to the pageowner guid
 * @uses $vars['entity_type']    Optional. Type of the entity. Used for determining container information
 * @uses $vars['entity_subtype'] Optional. Subtype of the entity. Used for determining container information
 * @uses $vars['container_info'] Optional. Helptext to show related to the container_guid
 */

$options = [
	'name' => 'container_guid',
	'value' => elgg_get_page_owner_guid(),
];

$options = array_merge($options, $vars);

echo elgg_view('input/hidden', $options);

$info = elgg_extract('container_info', $vars);
if ($info === false) {
	return;
}

$container = get_entity((int) $options['value']);
if (empty($info) && $container instanceof \ElggGroup) {
	$language_keys = [];
	
	$entity_type = elgg_extract('entity_type', $vars);
	$entity_subtype = elgg_extract('entity_subtype', $vars);
	
	if (!empty($entity_type) && !empty($entity_subtype)) {
		$language_keys[] = "input:container_guid:{$entity_type}:{$entity_subtype}:info";
	}
	
	$language_keys[] = 'input:container_guid:info';
	
	foreach ($language_keys as $key) {
		if (!elgg_language_key_exists($key)) {
			continue;
		}
		
		$info = elgg_echo($key, [elgg_view_entity_url($container)]);
		break;
	}
}

if (empty($info)) {
	return;
}

echo elgg_format_element('span', ['class' => 'elgg-subtext'], $info);
