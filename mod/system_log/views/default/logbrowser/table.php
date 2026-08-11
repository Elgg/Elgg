<?php
/**
 * Log browser table
 */

$log_entries = elgg_extract('log_entries', $vars);
if (empty($log_entries)) {
	echo elgg_view_no_results();
	return true;
}

$show_ip = (bool) elgg_get_plugin_setting('enable_ip_logging', 'system_log');

$header = [];
$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:date'));
if ($show_ip) {
	$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:ip_address'));
}

$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:user:name'));
$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:user:guid'));
$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:object'));
$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:object:id'));
$header[] = elgg_format_element('th', [], elgg_echo('logbrowser:action'));

$header = elgg_format_element('tr', [], implode(PHP_EOL, $header));
$header = elgg_format_element('thead', [], $header);

$rows = [];

/** @var \Elgg\SystemLog\SystemLogEntry $entry */
foreach ($log_entries as $entry) {
	$user = $entry->performed_by_guid ? get_entity($entry->performed_by_guid) : null;
	if ($user instanceof \ElggUser) {
		$user_link = elgg_view_entity_url($user);
		$user_guid_link = elgg_view_url(elgg_generate_url('admin', [
			'segments' => 'administer_utilities/logbrowser',
			'user_guid' => $user->guid,
		]), $user->guid);
	} else {
		$user_link = '&nbsp;';
		$user_guid_link = '&nbsp;';
	}

	$object = $entry->getObject();
	if (is_callable([$object, 'getURL'])) {
		$object_link = elgg_view_url($object->getURL(), $entry->object_class);
	} else {
		$object_link = $entry->object_class;
	}
	
	$object_id_link = elgg_view_url(elgg_generate_url('admin', [
		'segments' => 'administer_utilities/logbrowser',
		'object_id' => $entry->object_id,
	]), $entry->object_id);
	
	$row = elgg_format_element('td', ['class' => 'log-entry-time'], date('r', $entry->time_created));
	if ($show_ip) {
		$row .= elgg_format_element('td', ['class' => 'log-entry-ip-address'], $entry->ip_address ?: '&nbsp;');
	}
	
	$row .= elgg_format_element('td', ['class' => 'log-entry-user'], $user_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-guid'], $user_guid_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-object'], $object_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-guid'], $object_id_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-action'], $entry->event);
	
	$rows[] = elgg_format_element('tr', [], $row);
}

$body = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

echo elgg_format_element('table', ['class' => 'elgg-table'], $header . $body);
