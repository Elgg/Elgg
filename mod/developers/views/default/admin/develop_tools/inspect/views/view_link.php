<?php

$view = $vars['view'];

$text = elgg_extract('text', $vars, $view);

$id = "v_" . str_replace('/', '---', $view);

$href = "?inspect_type=Views#{$id}";

echo elgg_view('output/url', array(
	'href' => $href,
	'text' => $text,
));
