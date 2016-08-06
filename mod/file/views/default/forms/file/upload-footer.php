<?php

$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$submit_label = elgg_echo('save');
} else {
	$submit_label = elgg_echo('upload');
}

echo elgg_view_input('submit', [
	'value' => $submit_label,
	'field_class' => 'elgg-foot',
]);
