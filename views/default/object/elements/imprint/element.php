<?php

$icon_name = elgg_extract('icon_name', $vars, false);

$icon = '';
if ($icon_name !== false) {
	$icon = elgg_view_icon($icon_name);
}

$content = elgg_extract('content', $vars, '');

$result = $icon . $content;
if (elgg_is_empty($result)) {
	return;
}

echo elgg_format_element('span', [
	'class' => elgg_extract_class($vars),
], $result);
