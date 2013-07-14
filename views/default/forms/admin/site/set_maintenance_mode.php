<?php
/**
 * Form body for setting robots.txt
 */

$site = elgg_get_site_entity();
$mode = (int)elgg_get_config('elgg_maintenance_mode', null);

$message = $site->getPrivateSetting('elgg_maintenance_message');
if (!$message) {
	$message = elgg_echo('admin:maintenance_mode:default_message');
}

echo '<p>' . elgg_echo('admin:maintenance_mode:instructions') . '</p>';

echo '<div><label>' . elgg_echo('admin:maintenance_mode:mode_label') . ': ';
echo elgg_view('input/select', array(
	'name' => 'mode',
	'options_values' => array(
		'1' => elgg_echo('on'),
		'0' => elgg_echo('off'),
	),
	'value' => $mode,
));
echo '</label></div>';

echo '<div><label for="message">' . elgg_echo('admin:maintenance_mode:message_label') . ':</label><br>';
echo elgg_view('input/longtext', array(
	'name' => 'message',
	'id' => 'message',
	'value' => $message,
));
echo '</div>';

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
