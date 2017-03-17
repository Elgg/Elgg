<?php
/**
 * Displays breadcrumbs.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['breadcrumbs'] Breadcrumbs
 *                            If not set, will use breadcrumbs from the stack
 *                            pushed by elgg_push_breadcrumb()
 *                            <code>
 *                            [
 *                               [
 *                                  'title' => 'Breadcrumb title',
 *                                  'link' => '/path/to/page',
 *                               ],
 *                            ]
 *                            </code>
 * @uses $vars['class']       Optional class to add to the wrapping <ul>
 *
 * @see elgg_push_breadcrumb
 * @see elgg_get_breadcrumbs
 */

$breadcrumbs = elgg_extract('breadcrumbs', $vars);
$breadcrumbs = elgg_get_breadcrumbs($breadcrumbs);

if (!is_array($breadcrumbs) || empty($breadcrumbs)) {
	return;
}

$attrs['class'] = elgg_extract_class($vars, ['elgg-menu', 'elgg-breadcrumbs']);
$lis = '';

foreach ($breadcrumbs as $breadcrumb) {
	// We have to escape text (without double-encoding). Titles in core plugins are HTML escaped
	// on input, but we can't guarantee that other users of this view and of elgg_push_breadcrumb()
	// will do so.
	if (!empty($breadcrumb['link'])) {
		$crumb = elgg_view('output/url', [
			'href' => $breadcrumb['link'],
			'text' => $breadcrumb['title'],
			'encode_text' => true,
			'is_trusted' => true,
		]);
	} else {
		$crumb = htmlspecialchars($breadcrumb['title'], ENT_QUOTES, 'UTF-8', false);
	}

	$lis .= "<li>$crumb</li>";
}

echo elgg_format_element('ul', $attrs, $lis);
