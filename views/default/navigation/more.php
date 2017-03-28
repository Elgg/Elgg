<?php

/**
 * More link
 * Uses all vars accepted by output/url
 */

$link = elgg_view('output/url', $vars);
if (!$link) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-widget-more elgg-list-more',
], $link);