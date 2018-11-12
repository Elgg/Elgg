<?php
/**
 * Displays information about the author, the time and the access of the post
 *
 * @uses $vars['entity'] The entity to show the imprint for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$imprint = elgg_view('object/elements/imprint/contents', $vars);

if (elgg_is_empty($imprint)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-imprint',
], $imprint);
