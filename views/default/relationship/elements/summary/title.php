<?php
/**
 * Output relationship title
 *
 * @uses $vars['relationship'] the relationship
 * @uses $vars['title']        title (false for no title, '' for default title)
 */

$title = elgg_extract('title', $vars, '');
if ($title === false) {
	return;
}

$relationship = elgg_extract('relationship', $vars);
if ($title === '' && $relationship instanceof ElggRelationship) {
	$entity_one = get_entity($relationship->guid_one);
	$entity_two = get_entity($relationship->guid_two);
	if (empty($entity_one) || empty($entity_two)) {
		return;
	}
	
	$key = false;
	$keys = [
		"relationship:{$relationship->relationship}",
		'relationship:default',
	];
	foreach ($keys as $try_key) {
		if (elgg_language_key_exists($try_key)) {
			$key = $try_key;
			break;
		}
	}
	
	if (!empty($key)) {
		$title = elgg_echo($key, [elgg_view_entity_url($entity_one), elgg_view_entity_url($entity_two)]);
	}
}

echo elgg_format_element('div', ['class' => [
	'elgg-listing-summary-title',
]], $title);
