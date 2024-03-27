<?php

$body = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('config:content:comments_max_depth'),
	'#help' => elgg_echo('config:content:comments_max_depth:help'),
	'name' => 'comments_max_depth',
	'value' => elgg_get_config('comments_max_depth'),
	'options_values' => [
		0 => elgg_echo('config:content:comments_max_depth:none'),
		2 => 2,
		3 => 3,
		4 => 4,
	],
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:content:comment_box_collapses'),
	'#help' => elgg_echo('config:content:comment_box_collapses:help'),
	'name' => 'comment_box_collapses',
	'checked' => (bool) elgg_get_config('comment_box_collapses'),
	'switch' => true,
	'value' => 1,
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:content:comments_latest_first'),
	'#help' => elgg_echo('config:content:comments_latest_first:help'),
	'name' => 'comments_latest_first',
	'checked' => (bool) elgg_get_config('comments_latest_first'),
	'switch' => true,
	'value' => 1,
]);

$body .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('config:content:comments_per_page'),
	'name' => 'comments_per_page',
	'value' => elgg_get_config('comments_per_page'),
	'min' => 1,
	'step' => 1,
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:content:comments_group_only'),
	'name' => 'comments_group_only',
	'checked' => (bool) elgg_get_config('comments_group_only'),
	'switch' => true,
	'value' => 1,
]);

echo elgg_view_module('info', elgg_echo('admin:legend:comments'), $body);
