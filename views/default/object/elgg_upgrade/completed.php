<?php
/**
 * Completed view of an ElggUpgrade
 *
 * @uses $vars['entity'] the ElggUpgrade
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUpgrade) {
	return;
}

$imprint = [];

$imprint[] = [
	'icon_name' => 'flag-checkered',
	'content' => elgg_view('output/date', [
		'value' => $entity->getCompletedTime(),
		'format' => elgg_echo('friendlytime:date_format'),
	]),
];

/* @var $batch Elgg\Upgrade\Batch */
$batch = $entity->getBatch();
if (!empty($batch)) {
	if ($batch->shouldBeSkipped()) {
		$imprint[] = [
			'icon_name' => 'info',
			'content' => elgg_echo('upgrade:should_be_skipped'),
		];
	} else {
		$count = $batch->countItems();
		if (!empty($count)) {
			$imprint[] = [
				'icon_name' => 'hashtag',
				'content' => elgg_echo('upgrade:count_items', [$count]),
			];
		}
	}
}

$params = [
	'entity' => $entity,
	'title' => $entity->getDisplayName(),
	'content' => elgg_echo($entity->description),
	'imprint' => $imprint,
	'byline' => false,
	'access' => false,
];
$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
