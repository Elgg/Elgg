<?php
/**
 * Form body for setting robots.txt
 */

$site = elgg_get_site_entity();

echo '<div>';
echo elgg_echo('admin:robots.txt:instructions');
echo elgg_view('input/plaintext', array(
	'name' => 'text',
	'value' => $site->getPrivateSetting('robots.txt'),
));
echo '</div>';

echo '<div>';
echo elgg_echo('admin:robots.txt:plugins');
echo elgg_view('input/plaintext', array(
	'value' => elgg_trigger_plugin_hook('robots.txt', 'site', array('site' => $site), ''),
	'readonly' => true,
));
echo '</div>';

echo '<div>';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
