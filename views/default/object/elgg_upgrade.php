<?php
/**
 * ElggUpgrade view
 */

$entity = elgg_extract('entity', $vars);
/* @var $entity \ElggUpgrade */

$batch = $entity->getBatch();
if (!$batch) {
	// Something went wrong with class resolution
	return;
}

$data = elgg_format_element(
	'span',
	[
		'class' => 'upgrade-data hidden',
		'data-total' => $batch->countItems(),
	]
);

$timer = elgg_format_element(
	'span',
	['class' => 'upgrade-timer'],
	'00:00:00'
);

$counter = elgg_format_element(
	'span',
	['class' => 'upgrade-counter float-alt'],
	"0/{$batch->countItems()}"
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
	'class' => 'upgrade-messages elgg-message elgg-state-error alert alert-danger hidden',
]);

$params = [
	'entity' => $entity,
	'title' => elgg_echo($entity->title),
	'subtitle' => elgg_echo($entity->description),
	'content' => $data . $counter . $timer . $progressbar . $errors_link . $errors,
];

$body = elgg_view('object/elements/summary', $params + $vars);

echo elgg_view_image_block(false, $body, $vars);
