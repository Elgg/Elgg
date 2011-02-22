<?php
/**
 * A date-time view for the blog publication date.
 *
 * not being used
 */

// default date to current time
$publish_date = ($vars['value']) ? $vars['value'] : time();

$months = array();
for ($i=1; $i <= 12; $i++) {
	$value = str_pad($i, 2, '0', STR_PAD_LEFT);
	$months[$value] = date('M', strtotime("$value/01/2010"));
}

$month = elgg_view('input/dropdown', array(
	'name' => 'publish_month',
	'value' => date('m', $publish_date),
	'options_values' => $months,
	'class' => 'elgg-input-datetime-month',
));

$day = elgg_view('input/text', array(
	'name' => 'publish_day',
	'value' => date('d', $publish_date),
	'class' => 'elgg-input-datetime-day',
));

$year = elgg_view('input/text', array(
	'name' => 'publish_year',
	'value' => date('Y', $publish_date),
	'class' => 'elgg-input-datetime-year',
));

$hour = elgg_view('input/text', array(
	'name' => 'publish_hour',
	'value' => date('H', $publish_date),
	'class' => 'elgg-input-datetime-hour',
));

$minute = elgg_view('input/text', array(
	'name' => 'publish_minute',
	'value' => date('i', $publish_date),
	'class' => 'elgg-input-datetime-minute',
));

echo "$month $day, $year @ $hour:$minute";
