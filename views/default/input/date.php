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
 * @uses $vars['datepicker_options'] An array of options to pass to the jQuery UI datepicker
 * @uses $vars['format']    Date format, default Y-m-d (2018-01-30)
 */
$vars['class'] = elgg_extract_class($vars, 'elgg-input-date');

$defaults = [
	'value' => '',
	'disabled' => false,
	'timestamp' => false,
	'autocomplete' => 'off',
	'type' => 'text',
	'format' => elgg_get_config('date_format', elgg_echo('input:date_format')),
];

$vars = array_merge($defaults, $vars);

$timestamp = elgg_extract('timestamp', $vars);
unset($vars['timestamp']);

$format = elgg_extract('format', $vars, $defaults['format'], false);
unset($vars['format']);

$name = elgg_extract('name', $vars);
$value = elgg_extract('value', $vars);

$value_date = '';
$value_timestamp = '';

if ($value) {
	try {
		$dt = \Elgg\Values::normalizeTime($value);

		$value_date = $dt->format($format);
		$value_timestamp = $dt->getTimestamp();
	} catch (DataFormatException $ex) {
	}
}

if ($timestamp) {
	if (!isset($vars['id'])) {
		$vars['id'] = $name;
	}
	echo elgg_view('input/hidden', [
		'name' => $name,
		'value' => $value_timestamp,
		'rel' => elgg_extract('id', $vars),
	]);
	$vars['class'][] = 'elgg-input-timestamp';
	unset($vars['name']);
}

$vars['value'] = $value_date;

$datepicker_options = (array) elgg_extract('datepicker_options', $vars, []);
unset($vars['datepicker_options']);

if (empty($datepicker_options['dateFormat'])) {
	$datepicker_options['dateFormat'] = elgg_get_config('date_format_datepicker', elgg_echo('input:date_format:datepicker'));
}

$vars['data-datepicker-opts'] = $datepicker_options ? json_encode($datepicker_options) : '';

echo elgg_format_element('input', $vars);

if (isset($vars['id'])) {
	$selector = "#{$vars['id']}";
} else {
	$selector = ".elgg-input-date[name='{$name}']";
}
?>
<script>
	require(['input/date'], function (datepicker) {
		datepicker.init(<?= json_encode($selector) ?>);
	});
</script>
