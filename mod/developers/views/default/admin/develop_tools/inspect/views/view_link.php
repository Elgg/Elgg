<?php

$view = $vars['view'];

$text = elgg_extract('text', $vars, $view);

$id = "z" . md5($view);

$href = "admin/develop_tools/inspect?inspect_type=Views#{$id}";

if (get_input('inspect_type')) {
	// don't lose the viewtype
	$href = elgg_http_add_url_query_elements($href, [
		'type' => get_input('type')
	]);
}

echo elgg_view_url($href, $text);
