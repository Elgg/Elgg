<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 *
 * @uses string $vars['time']           Unix-style epoch timestamp
 * @uses int    $vars['number_of_days'] (optional) number of days before friendly time switches to a date format
 */

$timestamp = elgg_extract('time', $vars);
		
$default_friendly_time_number_of_days = elgg_get_config('friendly_time_number_of_days', 30);
$friendly_time_number_of_days = (int) elgg_extract('number_of_days', $vars, $default_friendly_time_number_of_days);

if (strtotime("-{$friendly_time_number_of_days}days") < $timestamp) {
	$output = elgg_get_friendly_time($timestamp);
} else {
	$output = date(elgg_echo('friendlytime:date_format:short'), $timestamp);
}

$attributes = [
	'title' => date(elgg_echo('friendlytime:date_format'), $timestamp),
	'datetime' => date('c', $timestamp),
];

echo elgg_format_element('time', $attributes, $output);
