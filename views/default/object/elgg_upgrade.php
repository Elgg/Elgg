<?php
/**
 * ElggUpgrade view
 */

$entity = elgg_extract('entity', $vars);

$total = $entity->getUpgrade()->getNumRemaining();

$data = elgg_format_element(
	'span',
	array(
		'class' => 'upgrade-data hidden',
		'data-total' => $total,
	)
);

$timer = elgg_format_element(
	'span',
	array('class' => 'upgrade-timer'),
	'00:00:00'
);

$counter = elgg_format_element(
	'span',
	array('class' => 'upgrade-counter float-alt'),
	"0/$total"
);

$progressbar = elgg_format_element('div', array(
	'class' => 'elgg-progressbar',
));

$errors_link = elgg_view('output/url', array(
	'href' => "#upgrade-errors-{$entity->guid}",
	'text' => elgg_echo('upgrade:error_count', array(0)),
	'rel' => 'toggle',
	'class' => 'upgrade-error-counter',
));

$errors = elgg_format_element('ul', array(
	'id' => "upgrade-errors-{$entity->guid}",
	'class' => 'upgrade-messages elgg-message elgg-state-error hidden',
));

$params = array(
	'entity' => $entity,
	'title' => elgg_echo($entity->title),
	'subtitle' => elgg_echo($entity->description),
	'content' => $data . $counter . $timer . $progressbar . $errors_link . $errors,
);

$body = elgg_view('object/elements/summary', $params + $vars);

echo elgg_view_image_block(false, $body, $vars);
