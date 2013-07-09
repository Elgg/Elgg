<?php
/**
 * Form body for setting robots.txt
 */

$site = elgg_get_site_entity();
$mode = (int)elgg_get_config('elgg_maintenance_mode', null);
if ($mode) {
	$button_text = elgg_echo('disable');
	$status = elgg_echo('admin:maintenance_mode:on');
} else {
	$button_text = elgg_echo('enable');
	$status = elgg_echo('admin:maintenance_mode:off');
}

$message = $site->getPrivateSetting('elgg_maintenance_message');
if (!$message) {
	$message = elgg_echo('admin:maintenance_mode:message');
}

echo '<p><em>' . $status . '</em><p>';

echo '<div>';
echo '<p>' . elgg_echo('admin:maintenance_mode:instructions') . '</p>';
echo elgg_view('input/longtext', array(
	'name' => 'message',
	'value' => $message,
));
echo '</div>';

echo '<div>';
// mode is 1 for on and 0 for off - this sets it to opposite of current
echo elgg_view('input/hidden', array('name' => 'mode', 'value' => 1 - $mode));
echo elgg_view('input/submit', array('value' => $button_text));
echo '</div>';
