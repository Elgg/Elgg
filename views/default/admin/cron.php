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
	if (!empty($msg)) {
		$msg = nl2br($msg);
		
		if (elgg_in_context('widgets')) {
			$msg = elgg_format_element('div', [], $msg);
			$msg = elgg_view('output/url', [
				'href' => false,
				'text' => false,
				'icon' => 'info',
				'class' => ['elgg-lightbox'],
				'data-colorbox-opts' => json_encode(['html' => $msg]),
			]);
		}
	}
	
	$row[] = elgg_format_element('td', [], $msg);
	
	$table_content .= elgg_format_element('tr', [], implode(PHP_EOL, $row));
}

$period_hd = elgg_echo('admin:cron:period');
$friendly_hd = elgg_echo('admin:cron:friendly');
$date_hd = elgg_echo('admin:cron:date');
$msg_hd = elgg_echo('admin:cron:msg');

$table = <<<HTML
<table class="elgg-table">
	<thead>
		<tr>
			<th>$period_hd</th>
			<th>$friendly_hd</th>
			<th>$date_hd</th>
			<th>$msg_hd</th>
		</tr>
	</thead>
	<tbody>
		$table_content
	</tbody>
</table>
HTML;

echo elgg_view_module('info', elgg_echo('admin:cron:record'), $table);
