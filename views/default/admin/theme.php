<?php

if (elgg_get_config('custom_theme_vars')) {
	elgg_register_menu_item('title', [
		'name' => 'theme:reset',
		'text' => elgg_echo('reset'),
		'href' => elgg_generate_action_url('admin/site/theme', ['vars' => []]),
		'link_class' => 'elgg-button elgg-button-action',
		'confirm' => true,
	]);
}

echo elgg_view_form('admin/site/theme', $vars);
