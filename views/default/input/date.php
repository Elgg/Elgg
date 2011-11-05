<?php
/**
 * Elgg date input
 * Displays a text field with a popup date picker.
 *
 * The elgg.ui JavaScript library initializes the jQueryUI datepicker based
 * on the CSS class .elgg-input-date. It uses the ISO 8601 standard for date
 * representation: yyyy-mm-dd.
 *
 * Unix timestamps are supported by setting the 'timestamp' parameter to true.
 * The date is still displayed to the user in a text format but is submitted as
 * a unix timestamp in seconds.
 *
 * @uses $vars['value']     The current value, if any (as a unix timestamp)
 * @uses $vars['class']     Additional CSS class
 * @uses $vars['timestamp'] Store as a Unix timestamp in seconds. Default = false
 *                          Note: you cannot use an id with the timestamp option.
 */

//@todo popup_calendar deprecated in 1.8.  Remove in 2.0
if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-date popup_calendar {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-date popup_calendar";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
	'timestamp' => false,
);

$vars = array_merge($defaults, $vars);

$timestamp = $vars['timestamp'];
unset($vars['timestamp']);

if ($timestamp) {
	echo elgg_view('input/hidden', array(
		'name' => $vars['name'],
		'value' => $vars['value'],
	));

	$vars['class'] = "{$vars['class']} elgg-input-timestamp";
	$vars['id'] = $vars['name'];
	unset($vars['name']);
	unset($vars['internalname']);
}

// convert timestamps to text for display
if (is_numeric($vars['value'])) {
	$vars['value'] = gmdate('Y-m-d', $vars['value']);
}

$attributes = elgg_format_attributes($vars);
echo "<input type=\"text\" $attributes />";
