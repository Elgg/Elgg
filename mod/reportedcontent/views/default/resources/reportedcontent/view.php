<?php

$guid = (int) elgg_extract('guid', $vars);

/** @var \ElggReportedContent $entity */
$entity = elgg_entity_gatekeeper($guid, 'object', 'reported_content');

if ($entity->state === 'active') {
	elgg_push_breadcrumb(elgg_echo('reportedcontent:new'), elgg_normalize_url('admin/administer_utilities/reportedcontent'));
} else {
	elgg_push_breadcrumb(elgg_echo('reportedcontent:archived_reports'), elgg_normalize_url('admin/administer_utilities/reportedcontent/archive'));
}

$sidebar = elgg_view('reportedcontent/sidebar', ['entity' => $entity]);

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity),
	'entity' => $entity,
	'show_owner_block' => false,
	'sidebar' => $sidebar ?: false,
	'filter_id' => 'reported_content/view',
], 'admin');
