<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 * 
 * @uses string $vars['time'] Unix-style epoch timestamp
 */

$diff = time() - ((int) $vars['time']);

$minute = 60;
$hour = $minute * 60;
$day = $hour * 24;

if ($diff < $minute) {
	$friendly_time = elgg_echo("friendlytime:justnow");
} else if ($diff < $hour) {
	$diff = round($diff / $minute);
	if ($diff == 0) {
		$diff = 1;
	}
	
	if ($diff > 1) {
		$friendly_time = sprintf(elgg_echo("friendlytime:minutes"), $diff);
	} else {
		$friendly_time = sprintf(elgg_echo("friendlytime:minutes:singular"), $diff);
	}
} else if ($diff < $day) {
	$diff = round($diff / $hour);
	if ($diff == 0) {
		$diff = 1;
	}

	if ($diff > 1) {
		$friendly_time = sprintf(elgg_echo("friendlytime:hours"), $diff);
	} else {
		$friendly_time = sprintf(elgg_echo("friendlytime:hours:singular"), $diff);
	}
} else {
	$diff = round($diff / $day);
	if ($diff == 0) {
		$diff = 1;
	}

	if ($diff > 1) {
		$friendly_time = sprintf(elgg_echo("friendlytime:days"), $diff);
	} else {
		$friendly_time = sprintf(elgg_echo("friendlytime:days:singular"), $diff);
	}
}

$timestamp = htmlentities(date(elgg_echo('friendlytime:date_format'), $vars['time']));

echo "<acronym title=\"$timestamp\">$friendly_time</acronym>";
