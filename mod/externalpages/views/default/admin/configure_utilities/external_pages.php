<?php
/**
 * Admin section for editing external pages
 */

$page = get_input('page', 'about');
if (!in_array($page, \ElggExternalPage::getAllowedPageNames())) {
	throw new \Elgg\Exceptions\Http\PageNotFoundException();
}

$objects = elgg_get_entities([
	'type' => 'object',
	'subtype' => \ElggExternalPage::SUBTYPE,
	'metadata_name_value_pairs' => ['title' => $page],
	'limit' => 1,
]);

$menu_vars = $vars;
$menu_vars['class'] = 'elgg-tabs';
$menu_vars['page'] = $page;
echo elgg_view_menu('external_pages', $menu_vars);

echo elgg_view_form('external_page/edit', [], [
	'page' => $page,
	'entity' => elgg_extract(0, $objects),
]);
