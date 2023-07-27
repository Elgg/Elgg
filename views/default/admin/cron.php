<?php
/**
 * Cron statistics
 */

$cron_service = _elgg_services()->cron;
$periods = $cron_service->getConfiguredIntervals(true);

$table_content = '';
foreach ($periods as $period) {
	$row = [];
	
	// name
	$row[] = elgg_format_element('td', [], elgg_echo("interval:$period"));
	
	// last completed (friendly) and full date
	$ts = $cron_service->getLog('completion', $period);
	if (!elgg_is_empty($ts)) {
		$row[] = elgg_format_element('td', [], elgg_view_friendly_time((int) $ts));
		$row[] = elgg_format_element('td', [], elgg_view('output/date', [
			'value' => $ts,
			'format' => DATE_RFC2822,
		]));
	} else {
		$row[] = elgg_format_element('td', [], elgg_echo('never'));
		$row[] = elgg_format_element('td', [], '&nbsp;');
	}

	// cron output
	$msg = $cron_service->getLog('output', $period);
	$msg_class = [];
	if (!empty($msg)) {
		$msg = nl2br($msg);
		
		if (elgg_in_context('widgets')) {
			$msg = elgg_format_element('div', [], $msg);
			$msg = elgg_view('output/url', [
				'href' => false,
				'text' => false,
				'title' => elgg_echo('more_info'),
				'icon' => 'info',
				'class' => ['elgg-lightbox'],
				'data-colorbox-opts' => json_encode(['html' => $msg]),
			]);
			
			$msg_class[] = 'center';
		}
	}
	
	$row[] = elgg_format_element('td', ['class' => $msg_class], $msg);
	
	$table_content .= elgg_format_element('tr', [], implode(PHP_EOL, $row));
}

$header = elgg_format_element('th', [], elgg_echo('admin:cron:period'));
$header .= elgg_format_element('th', [], elgg_echo('admin:cron:friendly'));
$header .= elgg_format_element('th', [], elgg_echo('admin:cron:date'));
$header .= elgg_format_element('th', [], elgg_echo('admin:cron:msg'));
$header = elgg_format_element('tr', [], $header);
$header = elgg_format_element('thead', [], $header);

$table_content = elgg_format_element('tbody', [], $table_content);

$table = elgg_format_element('table', ['class' => 'elgg-table'], $header . $table_content);

echo elgg_view_module('info', elgg_echo('admin:cron:record'), $table);
