<?php
/**
 * Outputs user summary content
 *
 * @uses $vars['content'] Summary content
 * @uses $vars['entity']  The ElggUser
 */

$content = elgg_extract('content', $vars, '');
if ($content === false) {
	return;
}

$entity = elgg_extract('entity', $vars);
if ($content === '' && $entity instanceof ElggUser) {
	if (elgg_view_exists('user/status')) {
		$content = elgg_view('user/status', [
			'entity' => $entity,
		]);
	}
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
