<?php
/**
 * Group module (also called a group widget)
 *
 * @uses $vars['title']          The title of the module
 * @uses $vars['content']        The module content
 * @uses $vars['all_link']       A link to list content
 * @uses $vars['add_link']       A link to create content
 * @uses $vars['entity']         The ElggGroup for which this module is shown (default: page owner)
 * @uses $vars['entity_type']    The entity type to use to generate title, links and content
 * @uses $vars['entity_subtype'] The entity subtype to use to generate title, links and content
 * @uses $vars['no_results']     The no result text to show in the listing (default: true => elgg_echo('notfound'))
 */

$group = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$group instanceof ElggGroup) {
	return;
}

$entity_type = elgg_extract('entity_type', $vars);
$entity_subtype = elgg_extract('entity_subtype', $vars);

$can_default = (!empty($entity_type) && !empty($entity_subtype));

// module title
$title = elgg_extract('title', $vars);
if (!isset($title) && $can_default) {
	$title = elgg_echo("collection:{$entity_type}:{$entity_subtype}:group");
}

// link to listing page
$menu = '';
$all_link = elgg_extract('all_link', $vars);
if (!isset($all_link) && $can_default) {
	$all_href = elgg_generate_url("collection:{$entity_type}:{$entity_subtype}:group", [
		'guid' => $group->guid,
	]);
	if (!empty($all_href)) {
		$all_link = elgg_view_url($all_href, elgg_echo('link:view:all'));
	}
}

if (!empty($all_link)) {
	$menu = elgg_format_element('span', [
		'class' => 'groups-widget-viewall',
	], $all_link);
}

// content
$content = elgg_extract('content', $vars);
if (!isset($content) && $can_default) {
	elgg_push_context('widgets');
	
	$content = elgg_list_entities([
		'type' => $entity_type,
		'subtype' => $entity_subtype,
		'container_guid' => $group->guid,
		'limit' => 6,
		'full_view' => false,
		'pagination' => false,
		'preload_containers' => false,
		'no_results' => elgg_extract('no_results', $vars, true),
	]);
	
	elgg_pop_context();
}

// link to add page
$footer = '';

$add_link = elgg_extract('add_link', $vars);
if (!isset($add_link) && $can_default) {
	$add_href = elgg_generate_url("add:{$entity_type}:{$entity_subtype}", [
		'guid' => $group->guid,
	]);
	if (!empty($add_href)) {
		$add_link = elgg_view_url($add_href, elgg_echo("add:{$entity_type}:{$entity_subtype}"));
	}
}

if (!empty($add_link) && !empty($entity_type) && !empty($entity_subtype)) {
	if ($group->canWriteToContainer(0, $entity_type, $entity_subtype)) {
		$footer = elgg_format_element('span', ['class' => 'elgg-widget-more'], $add_link);
	}
}

// draw module
echo elgg_view_module('info', $title, $content, [
	'menu' => $menu,
	'class' => 'elgg-module-group',
	'footer' => $footer,
]);
