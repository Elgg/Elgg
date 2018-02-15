<?php
/**
 * Elgg time input
 * Displays a select field with time options.
 *
 * Unix timestamps are supported by setting the 'timestamp' parameter to true.
 *
 * @uses $vars['value']     The current value, if any (as a unix timestamp)
 * @uses $vars['class']     Additional CSS class
 * @uses $vars['timestamp'] Store as a Unix timestamp in seconds. Default = false
 * @uses $vars['format']    Date format, default g:ia (4:44pm)
 * @uses $vars['step']      Interval/step (in seconds) between available time options (e.g. 15*60 for 15min)
 * @uses $vars['min']       Min available time in seconds (e.g. 2*60*60 for 2am)
 * @uses $vars['max']       Max available time in seconds (e.g. 23*60*60 for 11pm)
 */
$vars['class'] = elgg_extract_class($vars, 'elgg-input-time');

$defaults = [
	'value' => '',
	'timestamp' => false,
	'type' => 'select',
	'format' => elgg_get_config('time_format', elgg_echo('input:time_format')),
];

$vars = array_merge($defaults, $vars);

$timestamp = elgg_extract('timestamp', $vars);
unset($vars['timestamp']);

$format = elgg_extract('format', $vars, $defaults['format'], false);
unset($vars['format']);

$min = (int) elgg_extract('min', $vars, 0);
unset($vars['min']);

$max = (int) elgg_extract('max', $vars, 24 * 60 * 60);
unset($vars['max']);

$step = (int) elgg_extract('step', $vars, 15 * 60);
unset($vars['step']);

$value = elgg_extract('value', $vars);
$value_time = '';
$value_timestamp = '';
if ($value) {
	try {
		$dt = \Elgg\Values::normalizeTime($value);

		// round value to the closest divisible of a step
		$next_step_ts = (int) ceil($dt->getTimestamp() / $step) * $step;
		$dt->setTimestamp($next_step_ts);

		$value_timestamp = $dt->format('H') * 60 * 60 + $dt->format('i') * 60;
		$value_time = $dt->format($format);
	} catch (DataFormatException $ex) {
	}
}

if ($timestamp) {
	$vars['value'] = $value_timestamp;
} else {
	$vars['value'] = $value_time;
}

$hour_options = [];
$hour_options_ts = range($min, $max, $step);

$dt = new DateTime(null, new DateTimeZone('UTC'));

foreach ($hour_options_ts as $ts) {
	$dt->setTimestamp($ts);
	$key = ($timestamp) ? $dt->getTimestamp() : $dt->format($format);
	$hour_options[$key] = $dt->format($format);
}

$vars['options_values'] = $hour_options;

echo elgg_view('input/select', $vars);
