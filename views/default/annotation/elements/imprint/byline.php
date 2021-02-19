<?php
/**
 * Displays information about the owner of the annotation
 *
 * @uses $vars['annotation']              The annotation to show the byline for
 * @uses $vars['byline']                  Byline: true | false | string (default: false)
 *                                        If set to false, byline will not be rendered
 * @uses $vars['byline_owner_entity']     the owner entity to use for the byline (default: ElggAnnotation::getOwnerEntity())
 * @uses $vars['show_links']              Owner and container text should show as links (default: true)
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$show_links = elgg_extract('show_links', $vars, true);

$byline_str = elgg_extract('byline', $vars, false);
if ($byline_str === true) {
	$owner = elgg_extract('byline_owner_entity', $vars, $annotation->getOwnerEntity());
	if ($owner instanceof ElggEntity) {
		$owner_text = $show_links ? elgg_view_entity_url($owner) : $owner->getDisplayName();

		$byline_str = elgg_echo('byline', [$owner_text]);
	}
}

if (elgg_is_empty($byline_str)) {
	return;
}

echo elgg_view('object/elements/imprint/element', [
	'content' => $byline_str,
	'class' => 'elgg-listing-byline',
]);
