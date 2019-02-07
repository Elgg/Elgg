<?php
/**
 * Cron statistics
 */

$periods = elgg_get_config('elgg_cron_periods');
$cron_service = _elgg_services()->cron;

$table_content = '';
foreach ($periods as $period) {
	$row = [];
	
	// name
	$row[] = elgg_format_element('td', [], elgg_echo("interval:$period"));
	
	// last completed (friendly) and full date
	$ts = $cron_service->getLog('completion', $period);
	if ($ts) {
		$row[] = elgg_format_element('td', [], elgg_view_friendly_time($ts));
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
	if ($msg) {
		$msg = nl2br($msg);
	}
	
	if (!empty($msg) && elgg_in_context('widgets')) {
		$wrapped_message = elgg_format_element('div', [
			'id' => "cron_{$period}",
			'class' => 'hidden',
		], $msg);
		
		$msg = elgg_view('output/url', [
			'href' => "#cron_{$period}",
			'text' => elgg_echo('show'),
			'rel' => 'toggle',
		]) . $wrapped_message;
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
