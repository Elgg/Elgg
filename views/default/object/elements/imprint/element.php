<?php

$icon_name = elgg_extract('icon_name', $vars, false);

$icon = '';
if ($icon_name !== false) {
	$icon = elgg_view_icon($icon_name);
}

$content = elgg_extract('content', $vars, '');

echo elgg_format_element('span', [
	'class' => elgg_extract_class($vars),
], $icon . $content);
