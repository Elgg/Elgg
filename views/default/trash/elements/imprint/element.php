<?php
/**
 * Show a single imprint element
 *
 * @uses $vars['icon_name'] name of the imprint icon
 * @uses $vars['content']   the text of the imprint
 */

$icon_name = elgg_extract('icon_name', $vars, false);

$icon = '';
if ($icon_name !== false) {
	$icon = elgg_view_icon($icon_name);
}

$content = (string) elgg_extract('content', $vars, '');

$result = $icon . $content;
if (elgg_is_empty($result)) {
	return;
}

echo elgg_format_element('span', [
	'class' => elgg_extract_class($vars),
	'title' => elgg_extract('title', $vars),
], $result);
