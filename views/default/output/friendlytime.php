<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 *
 * @uses string $vars['time'] Unix-style epoch timestamp
 */

$friendly_time = elgg_get_friendly_time($vars['time']);
$attributes = array();
$attributes['title'] = date(elgg_echo('friendlytime:date_format'), $vars['time']);
$attributes['datetime'] = date('c', $vars['time']);
$attrs = elgg_format_attributes($attributes);

echo "<time $attrs>$friendly_time</time>";
