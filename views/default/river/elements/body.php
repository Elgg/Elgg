<?php
/**
 * Body of river item
 *
 * @uses $vars['item']        ElggRiverItem
 * @uses $vars['summary']     Alternate summary (the short text summary of action)
 * @uses $vars['message']     Optional message (usually excerpt of text)
 * @uses $vars['attachments'] Optional attachments (displaying icons or other non-text data)
 * @uses $vars['responses']   Alternate respones (comments, replies, etc.)
 */

$item = elgg_extract('item', $vars);
if (!($item instanceof \ElggRiverItem)) {
	return;
}

$metadata = elgg_extract('metadata', $vars);
if (!isset($metadata)) {
	$metadata = elgg_view_menu('river', [
		'item' => $item,
		'sort_by' => 'priority',
	]);
	
	$object = $item->getObjectEntity();
	if ($object) {
		$metadata .= elgg_view_menu('social', [
			'entity' => $object,
			'item' => $item,
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		]);
	}
}

if ($metadata) {
	$metadata = elgg_format_element('div', [
		'class' => 'elgg-river-metadata',
	], $metadata);
}

// river item header
$timestamp = elgg_view_friendly_time($item->getTimePosted());

$summary = elgg_extract('summary', $vars);
if ($summary === null) {
	$summary = elgg_view('river/elements/summary', [
		'item' => $vars['item'],
	]);
}

if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', [
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
	]);
}
$summary = trim($summary);

$message = elgg_extract('message', $vars);
if ($message !== null) {
	$message = "<div class=\"elgg-river-message\">$message</div>";
}

$attachments = elgg_extract('attachments', $vars);
if ($attachments !== null) {
	$attachments = "<div class=\"elgg-river-attachments clearfix\">$attachments</div>";
}

$responses = elgg_view('river/elements/responses', $vars);
if ($responses) {
	$responses = "<div class=\"elgg-river-responses\">$responses</div>";
}

echo <<<RIVER
$metadata
<div class="elgg-river-summary">$summary <span class="elgg-river-timestamp">$timestamp</span></div>
$message
$attachments
$responses
RIVER;
