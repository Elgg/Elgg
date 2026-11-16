<?php
/**
 * Pending view of an ElggUpgrade
 *
 * @uses $vars['entity'] the ElggUpgrade
 */

use Elgg\Upgrade\Batch;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUpgrade) {
	return;
}

$batch = $entity->getBatch();
if (!$batch) {
	// Something went wrong with class resolution
	return;
}

if ($batch->shouldBeSkipped()) {
	$content = elgg_view_message('notice', elgg_echo('upgrade:should_be_skipped:notice'), ['title' => false]);
} else {
	$count = $batch->countItems();

	$content = elgg_format_element('span', [
		'class' => ['upgrade-data', 'hidden'],
		'data-total' => $count,
	]);

	$content .= elgg_format_element('span', ['class' => ['upgrade-counter', 'float-alt']],
		$count === Batch::UNKNOWN_COUNT ? '0/???' : "0/{$count}"
	);

	$content .= elgg_format_element('span', ['class' => 'upgrade-timer'], '00:00:00');

	$content .= elgg_format_element('div', ['class' => 'elgg-progressbar']);

	$content .= elgg_view('output/url', [
		'href' => "#upgrade-errors-{$entity->guid}",
		'text' => elgg_echo('upgrade:error_count', [0]),
		'class' => ['elgg-toggle', 'upgrade-error-counter'],
	]);

	$content .= elgg_format_element('ul', [
		'id' => "upgrade-errors-{$entity->guid}",
		'class' => ['upgrade-messages', 'elgg-message', 'elgg-state-error', 'hidden'],
	]);
}

$params = [
	'entity' => $entity,
	'content' => $content,
];
$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
