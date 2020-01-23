<?php
/**
 * Wrapper for site pages content area
 *
 * @uses $vars['content']
 */

echo elgg_extract('content', $vars);

$referer = elgg_extract('HTTP_REFERER', $_SERVER);
if (elgg_strpos($referer, elgg_get_site_url()) !== 0) {
	return;
}

echo '<div class="mtm">';
echo elgg_view('output/url', [
	'text' => elgg_echo('back'),
	'href' => 'javascript: history.back()',
	'class' => 'float-alt',
]);
echo '</div>';
