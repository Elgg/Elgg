<?php
/**
 * Outputs object subtitle
 * @uses $vars['subtitle'] Subtitle
 *                         Accepts an HTML string or an array of subtitle elements
 *                         <code>
 *                           [
 *                             'by_line' => 'By John Doe 2 hours ago',
 *                             'comments_link' => '2 comments',
 *                             'categories' => 'Education',
 *                           ]
 *                         </code>
 * @uses $vars['subtitle_glue'] Character(s) or HTML string used to glue subtitle elements (default: &nbsp;)
 */
$subtitle = (array) elgg_extract('subtitle', $vars, []);
if (empty($subtitle)) {
	return;
}

$glue = elgg_extract('subtitle_glue', $vars, "&nbsp;");

// Remove empty elements
$subtitle = array_filter($subtitle);

array_walk($subtitle, function(&$elem, $key) {
	$elem = elgg_format_element([
		'#tag_name' => 'span',
		'#text' => $elem,
		'class' => 'elgg-subtitle-element',
		'data-subtitle-key' => is_string($key) ? $key : null,
	]);
});

?>
<div class="elgg-listing-summary-subtitle elgg-subtext"><?= implode($glue, $subtitle) ?></div>