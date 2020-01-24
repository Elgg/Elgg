<?php
/**
 * Outputs annotation summary content
 *
 * @uses $vars['content']    Summary content (false for no content, '' for default annotation value)
 * @uses $vars['annotation'] ElggAnnotation
 */

$content = elgg_extract('content', $vars, '');
if ($content === false) {
	return;
}

$annotation = elgg_extract('annotation', $vars);
if ($content === '' && $annotation instanceof ElggAnnotation) {
	$content = $annotation->value;
}

if (elgg_is_empty($content)) {
	return;
}

echo elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-content',
		'elgg-content',
	]
], $content);
