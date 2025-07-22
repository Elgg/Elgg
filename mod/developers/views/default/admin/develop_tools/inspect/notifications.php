<?php
/**
 * List all notification handlers
 */

use Elgg\Notifications\InstantNotificationEventHandler;

$data = elgg_extract('data', $vars);
if (empty($data)) {
	return;
}

$header = [
	elgg_format_element('th', [], elgg_echo('developers:inspect:notifications:type')),
	elgg_format_element('th', [], elgg_echo('developers:inspect:notifications:subtype')),
	elgg_format_element('th', [], elgg_echo('developers:inspect:notifications:action')),
	elgg_format_element('th', [], elgg_echo('developers:inspect:notifications:handler')),
	elgg_format_element('th', [], elgg_echo('developers:inspect:notifications:instant')),
];
$header = elgg_format_element('tr', [], implode(PHP_EOL, $header));
$header = elgg_format_element('thead', [], $header);

ksort($data, SORT_NATURAL);
$rows = [];
foreach ($data as $type => $subtypes) {
	ksort($subtypes, SORT_NATURAL);
	
	foreach ($subtypes as $subtype => $actions) {
		ksort($actions, SORT_NATURAL);
		
		foreach ($actions as $action => $handler) {
			$row = [
				elgg_format_element('td', [], $type),
				elgg_format_element('td', [], $subtype),
				elgg_format_element('td', [], $action),
				elgg_format_element('td', [], $handler),
			];
			
			if (is_a($handler, InstantNotificationEventHandler::class, true)) {
				$row[] = elgg_format_element('td', [], elgg_echo('option:yes'));
			} else {
				$row[] = elgg_format_element('td', [], '&nbsp;');
			}
			
			$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
		}
	}
}

$body = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

echo elgg_format_element('table', ['class' => 'elgg-table'], $header . $body);
