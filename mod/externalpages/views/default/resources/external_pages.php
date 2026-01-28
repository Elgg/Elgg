<?php

$page = (string) elgg_extract('page', $vars);
$page = strtolower($page);

if (elgg_is_admin_logged_in()) {
	elgg_register_menu_item('title', [
		'name' => 'edit',
		'icon' => 'edit',
		'text' => elgg_echo('edit'),
		'href' => "admin/configure_utilities/external_pages?type={$page}",
		'link_class' => ['elgg-button', 'elgg-button-action'],
	]);
}

$objects = elgg_get_entities([
	'type' => 'object',
	'subtype' => \ElggExternalPage::SUBTYPE,
	'metadata_name_value_pairs' => ['title' => $page],
	'limit' => 1,
]);

$entity = elgg_extract(0, $objects);

$title = elgg_language_key_exists("external_pages:{$page}") ? elgg_echo("external_pages:{$page}") : $page;
echo elgg_view_page($title, [
	'content' => elgg_view('output/longtext', [
		'value' => $entity?->description ?: elgg_echo('external_pages:notset'),
	]),
	'sidebar' => false,
	'entity' => $entity,
], 'walled_garden');
