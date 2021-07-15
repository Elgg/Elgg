<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 *
 * @uses string $vars['time']           Unix-style epoch timestamp
 * @uses string $vars['title']          (optional) title on the time element, will default to date/time formatted timestamp
 * @uses int    $vars['number_of_days'] (optional) number of days before friendly time switches to a date format
 */

use Elgg\Exceptions\DataFormatException;
use Elgg\Values;

$timestamp = elgg_extract('time', $vars);

try {
	$date = Values::normalizeTime($timestamp);
} catch (DataFormatException $e) {
	return;
}
		
$default_friendly_time_number_of_days = elgg_get_config('friendly_time_number_of_days', 30);
$friendly_time_number_of_days = (int) elgg_extract('number_of_days', $vars, $default_friendly_time_number_of_days);

if (strtotime("-{$friendly_time_number_of_days}days") < $timestamp) {
	$output = elgg_get_friendly_time($timestamp);
} else {
	$output = $date->formatLocale(elgg_echo('friendlytime:date_format:short'));
}

$attributes = [
	'title' => elgg_extract('title', $vars, $date->formatLocale(elgg_echo('friendlytime:date_format'))),
	'datetime' => $date->format('c'),
];

echo elgg_format_element('time', $attributes, $output);
