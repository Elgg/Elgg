<?php
/**
 * Button area for showing the add widgets panel
 */

$href = elgg_generate_url('widgets:add_panel', [
	'context' => elgg_get_context(),
	'context_stack' => elgg_get_context_stack(),
	'show_access' => elgg_extract('show_access', $vars, true),
	'owner_guid' => elgg_extract('owner_guid', $vars, elgg_get_page_owner_guid()),
	'new_widget_column' => elgg_extract('new_widget_column', $vars),
	'new_widget_position' => elgg_extract('new_widget_position', $vars),
]);

echo elgg_view_menu('title:widgets', [
	'items' => [
		[
			'name' => 'widgets_add',
			'href' => false,
			'text' => elgg_echo('widgets:add'),
			'icon' => 'cog',
			'link_class' => 'elgg-lightbox elgg-more',
			'data-colorbox-opts' => json_encode([
				'href' => $href,
				'maxWidth' => '900px',
				'maxHeight' => '90%',
			]),
		],
	],
	'class' => 'elgg-menu-hz',
]);
