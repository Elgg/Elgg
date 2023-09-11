<?php
/**
 * Cron statistics
 */

use Elgg\Values;

$cron_service = _elgg_services()->cron;
$periods = $cron_service->getConfiguredIntervals(true);

$in_widget = elgg_in_context('widgets');

$table_content = '';
foreach ($periods as $period) {
	$row = [];
	
	// name
	$row[] = elgg_format_element('td', [], elgg_echo("interval:{$period}"));
	
	// last completed (friendly) and full date
	$date = $cron_service->getLastCompletion($period);
	if (!elgg_is_empty($date)) {
		$row[] = elgg_format_element('td', [], elgg_view_friendly_time($date));
		$row[] = elgg_format_element('td', [], elgg_view('output/date', [
			'value' => $date,
			'format' => DATE_RFC2822,
		]));
	} else {
		$row[] = elgg_format_element('td', [], elgg_echo('never'));
		$row[] = elgg_format_element('td', [], '&nbsp;');
	}

	// cron output
	$logs = $cron_service->getLogs($period);
	$msg_class = [];
	$log_output = '&nbsp;';
	if (!empty($logs)) {
		$log_output = '';
		
		foreach ($logs as $filename => $contents) {
			$matches = [];
			preg_match('/(?<year>[0-9]{4})-(?<month>[0-9]{2})-(?<day>[0-9]{2})T(?<hour>[0-9]{2})-(?<minute>[0-9]{2})-(?<second>[0-9]{2})(?<offset>[-p])(?<offset_hour>[0-9]{2})-(?<offset_minute>[0-9]{2})\S+/', $filename, $matches);
			if ($matches['offset'] === 'p') {
				$matches['offset'] = '+';
			}
			
			$date_string = "{$matches['year']}-{$matches['month']}-{$matches['day']}T{$matches['hour']}:{$matches['minute']}:{$matches['second']}{$matches['offset']}{$matches['offset_hour']}:{$matches['offset_minute']}";
			$date = Values::normalizeTime($date_string);
			
			$contents = nl2br($contents);
			$contents = elgg_format_element('div', [], $contents);
			
			$log_output .= elgg_view('output/url', [
				'icon' => 'info',
				'text' => !$in_widget ? $date->format(DATE_RFC2822) : false,
				'title' => elgg_echo('more_info'),
				'href' => "#{$period}-{$date->getTimestamp()}",
				'class' => ['elgg-lightbox'],
				'data-colorbox-opts' => json_encode(['html' => $contents]),
			]);
			
			if ($in_widget) {
				$msg_class[] = 'center';
				break;
			}
			
			$log_output .= '<br />';
		}
	}
	
	$row[] = elgg_format_element('td', ['class' => $msg_class], $log_output);
	
	$table_content .= elgg_format_element('tr', [], implode(PHP_EOL, $row));
}

$header = elgg_format_element('th', [], elgg_echo('admin:cron:period'));
$header .= elgg_format_element('th', [], elgg_echo('admin:cron:friendly'));
$header .= elgg_format_element('th', [], elgg_echo('admin:cron:date'));
$header .= elgg_format_element('th', [], elgg_echo('admin:cron:msg'));
$header = elgg_format_element('tr', [], $header);
$header = elgg_format_element('thead', [], $header);

$table_content = elgg_format_element('tbody', [], $table_content);

echo elgg_format_element('table', ['class' => 'elgg-table'], $header . $table_content);
