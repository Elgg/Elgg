<?php

/**
 * View a friends collection
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['collection'] The access collection
 */
$collection = elgg_extract('collection', $vars);
if (empty($collection)) {
	return;
}

$members = get_members_of_access_collection($collection->id) ? : array();
$count = count($members);

$counter = elgg_format_element('span', ['class' => 'elgg-friends-collection-membership-count'], $count);
$title = "$collection->name ($counter)";

if (can_edit_access_collection($collection->id)) {
	$edit_link = elgg_view('output/url', array(
		'href' => '#elgg-friends-collection-form-' . $collection->id,
		'rel' => 'toggle',
		'text' => elgg_view_icon('edit'),
	));
	$controls = elgg_format_element('li', [], $edit_link);

	$delete_link = elgg_view('output/url', array(
		'href' => 'action/friends/collections/delete?collection=' . $collection->id,
		'text' => elgg_view_icon('delete'),
		'confirm' => true,
	));
	$controls .= elgg_format_element('li', [], $delete_link);
	$title .= elgg_format_element('ul', ['class' => 'elgg-menu-hz float-alt'], $controls);
}

$body .= elgg_view('core/friends/collection/membership', array(
	'collection' => $collection,
		));

$body .= elgg_view_form('friends/collections/edit', array(
	'id' => 'elgg-friends-collection-form-' . $collection->id,
	'class' => 'hidden',
		), array(
	'collection' => $collection,
		));

echo elgg_view_module('aside', $title, $body, array(
	'class' => 'elgg-friends-collection',
	'data-collection-id' => $collection->id,
));

elgg_require_js('core/friends/collection');
