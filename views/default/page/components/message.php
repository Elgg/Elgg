<?php
/**
 * Elgg message element
 *
 * @uses $vars['type']      The type of message (error, success, warning, help, notice, info)
 * @uses $vars['title']     Optional title text, will default to the type
 * @uses $vars['icon_name'] Optional iconname to override default icon
 * @uses $vars['body']      Content of the body
 * @uses $vars['class']     Optional additional class for message
 */

$type = elgg_extract('type', $vars, false);
$title = elgg_extract('title', $vars);
$body = elgg_extract('body', $vars, '');

if (empty($title) && empty($body)) {
	return;
}

$attrs = [
	'class' => elgg_extract_class($vars, 'elgg-message'),
];

if ($type) {
	$attrs['class'][] = "elgg-message-$type";
}

$default_icons = [
	'error' => 'exclamation-circle',
	'help' => 'question-circle',
	'notice' => 'info-circle',
	'info' => 'info-circle',
	'warning' => 'exclamation-triangle',
	'success' => 'check-circle',
];

$default_icon_name = elgg_extract($type, $default_icons);
$icon_name = elgg_extract('icon_name', $vars, $default_icon_name);

if (is_null($title) && !empty($type) && elgg_language_key_exists("messages:title:{$type}")) {
	$title = elgg_echo("messages:title:{$type}");
}

$header = '';
if (!empty($title) && !empty($icon_name)) {
	$header .= elgg_view_icon($icon_name, ['class' => 'elgg-message-icon']);
}

if (!empty($title)) {
	$header .= elgg_format_element('span', ['class' => 'elgg-message-title'], $title);
}

if (!empty($header)) {
	$header = elgg_format_element('div', ['class' => 'elgg-head'], $header);
}

if (!empty($body)) {
	$body = elgg_format_element('div', ['class' => 'elgg-body'], $body);
}

$contents = elgg_format_element('div', ['class' => 'elgg-inner'], $header . $body);

echo elgg_format_element('div', $attrs, $contents);
