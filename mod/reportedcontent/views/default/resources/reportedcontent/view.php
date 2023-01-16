<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'reported_content');

/* @var \ElggReportedContent $entity */
$entity = get_entity($guid);

if ($entity->state === 'active') {
	elgg_push_breadcrumb(elgg_echo('reportedcontent:new'), elgg_normalize_url('admin/administer_utilities/reportedcontent'));
} else {
	elgg_push_breadcrumb(elgg_echo('reportedcontent:archived_reports'), elgg_normalize_url('admin/administer_utilities/reportedcontent/archive'));
}

$sidebar = elgg_view('reportedcontent/sidebar', ['entity' => $entity]);

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity, [
		'full_view' => true,
		'show_responses' => true,
	]),
	'entity' => $entity,
	'show_owner_block' => false,
	'sidebar' => $sidebar ?: false,
	'filter_id' => 'reported_content/view',
], 'admin');
