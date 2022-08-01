<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 *
 * @uses string $vars['time']           A UNIX epoch timestamp, a date string or a DateTime object
 * @uses string $vars['time_updated']   A UNIX epoch timestamp, a date string or a DateTime object
 * @uses string $vars['title']          (optional) title on the time element, will default to date/time formatted timestamp
 * @uses int    $vars['number_of_days'] (optional) number of days before friendly time switches to a date format
 */

use Elgg\Exceptions\DataFormatException;
use Elgg\Values;

$time = elgg_extract('time', $vars);
$time_updated = elgg_extract('time_updated', $vars);
$date_updated = null;

try {
	$date = Values::normalizeTime($time);
	if (!empty($time_updated)) {
		$date_updated = Values::normalizeTime($time_updated);
	}
} catch (DataFormatException $e) {
	return;
}
		
$default_friendly_time_number_of_days = elgg_get_config('friendly_time_number_of_days');
$friendly_time_number_of_days = (int) elgg_extract('number_of_days', $vars, $default_friendly_time_number_of_days);

if (strtotime("-{$friendly_time_number_of_days}days") < $date->getTimestamp()) {
	$output = elgg_get_friendly_time($date->getTimestamp());
} else {
	$output = $date->formatLocale(elgg_echo('friendlytime:date_format:short'));
}

$title = $date->formatLocale(elgg_echo('friendlytime:date_format'));

if ($date_updated && ($date_updated->getTimestamp() > $date->getTimestamp() + 60)) {
	$output = elgg_echo('friendlytime:updated', [$output]);
	$title = elgg_echo('friendlytime:updated:title', [$title, $date_updated->formatLocale(elgg_echo('friendlytime:date_format'))]);
}

$attributes = [
	'title' => elgg_extract('title', $vars, $title),
	'datetime' => $date->format('c'),
];

echo elgg_format_element('time', $attributes, $output);
