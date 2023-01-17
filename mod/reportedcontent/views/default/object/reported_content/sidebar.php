<?php
/**
 * Elgg reported content object view for use in sidebar listing
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggReportedContent) {
	return;
}

$vars['access'] = false;
$vars['metadata'] = false;
$vars['imprint'] = (array) elgg_extract('imprint', $vars, []);
if ($entity->state !== 'active') {
	$vars['imprint'][] = [
		'icon_name' => 'archive',
		'content' => elgg_echo('reportedcontent:archived_reports'),
	];
}

echo elgg_view('object/elements/summary', $vars);
