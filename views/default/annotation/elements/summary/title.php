<?php
/**
 * Output annotation title
 *
 * @uses $vars['annotation'] ElggAnnotation
 * @uses $vars['title']      title (false for no title, '' for default title)
 */

$title = elgg_extract('title', $vars, '');
if ($title === false) {
	return;
}

$annotation = elgg_extract('annotation', $vars);
if ($title === '' && $annotation instanceof ElggAnnotation) {
	$owner = $annotation->getOwnerEntity();
	if (!$owner instanceof ElggEntity) {
		return;
	}
	
	$title = elgg_view_entity_url($owner);
}

if (elgg_is_empty($title)) {
	return;
}

echo elgg_format_element('div', ['class' => [
	'elgg-listing-summary-title',
]], $title);
