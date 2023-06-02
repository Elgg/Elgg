<?php
/**
 * Elgg reported content object view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggReportedContent) {
	return;
}

$report_address = elgg_view_url($entity->getAddress());

$vars['imprint'] = (array) elgg_extract('imprint', $vars, []);
$vars['access'] = false;

if (!empty($report_address)) {
	$vars['imprint'][] = [
		'icon_name' => 'globe',
		'content' => $report_address,
	];
}

if (!elgg_extract('full_view', $vars)) {
	$vars['content'] = elgg_get_excerpt((string) $entity->description);
	
	echo elgg_view('object/elements/summary', $vars);
	
	return;
}

$body = elgg_view('output/longtext', ['value' => $entity->description, 'class' => 'mbm']);

if (!empty($report_address)) {
	$body .= elgg_format_element('b', [], elgg_echo('reportedcontent:address')) . ': ' . $report_address;
}

$body .= elgg_view_message('info', elgg_echo('reportedcontent:comments:message'), ['title' => false, 'class' => ['mtl', 'mbn']]);

if ($entity->state !== 'active') {
	$vars['imprint'][] = [
		'icon_name' => 'archive',
		'content' => elgg_echo('reportedcontent:archived_reports'),
	];
}

$vars['metadata'] = false;
$vars['body'] = $body;
$vars['show_summary'] = true;

echo elgg_view('object/elements/full', $vars);
