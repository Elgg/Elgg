<?php
/**
 * Pending view of an ElggUpgrade
 *
 * @uses $vars['entity'] the ElggUpgrade
 */

use Elgg\Upgrade\Batch;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUpgrade) {
	return;
}

$batch = $entity->getBatch();
if (!$batch || $batch->shouldBeSkipped()) {
	// Something went wrong with class resolution
	return;
}

$count = $batch->countItems();

$data = elgg_format_element('span', [
		'class' => 'upgrade-data hidden',
		'data-total' => $count,
	]
);

$timer = elgg_format_element('span', ['class' => 'upgrade-timer'], '00:00:00');

$counter = elgg_format_element('span', ['class' => 'upgrade-counter float-alt'],
	$count === Batch::UNKNOWN_COUNT ? "0/???" : "0/$count"
);

$progressbar = elgg_format_element('div', [
	'class' => 'elgg-progressbar',
]);

$errors_link = elgg_view('output/url', [
	'href' => "#upgrade-errors-{$entity->guid}",
	'text' => elgg_echo('upgrade:error_count', [0]),
	'rel' => 'toggle',
	'class' => 'upgrade-error-counter',
]);

$errors = elgg_format_element('ul', [
	'id' => "upgrade-errors-{$entity->guid}",
	'class' => 'upgrade-messages elgg-message elgg-state-error hidden',
]);

$params = [
	'entity' => $entity,
	'title' => $entity->getDisplayName(),
	'subtitle' => elgg_echo($entity->description),
	'content' => $data . $counter . $timer . $progressbar . $errors_link . $errors,
];
$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
