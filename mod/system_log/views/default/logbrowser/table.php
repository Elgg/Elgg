<?php
/**
 * Log browser table
 */

$log_entries = elgg_extract('log_entries', $vars);

if (empty($log_entries)) {
	echo elgg_echo('notfound');
	return true;
}

?>

<table class="elgg-table">
	<thead>
		<tr>
			<th><?php echo elgg_echo('logbrowser:date'); ?></th>
			<th><?php echo elgg_echo('logbrowser:ip_address'); ?></th>
			<th><?php echo elgg_echo('logbrowser:user:name'); ?></th>
			<th><?php echo elgg_echo('logbrowser:user:guid'); ?></th>
			<th><?php echo elgg_echo('logbrowser:object'); ?></th>
			<th><?php echo elgg_echo('logbrowser:object:id'); ?></th>
			<th><?php echo elgg_echo('logbrowser:action'); ?></th>
		</tr>
	</thead>
<?php

/** @var $entry Elgg\SystemLog\SystemLogEntry */
foreach ($log_entries as $entry) {
	$user = get_entity($entry->performed_by_guid);
	if ($user) {
		$user_link = elgg_view_entity_url($user);
		$user_guid_link = elgg_view_url("admin/administer_utilities/logbrowser?user_guid={$user->guid}", $user->guid);
	} else {
		$user_guid_link = $user_link = '&nbsp;';
	}

	$object = $entry->getObject();
	if (is_callable([$object, 'getURL'])) {
		$object_link = elgg_view_url($object->getURL(), $entry->object_class);
	} else {
		$object_link = $entry->object_class;
	}
	
	$object_id_link = elgg_view_url(elgg_http_add_url_query_elements('admin/administer_utilities/logbrowser', ['object_id' => $entry->object_id]), $entry->object_id);
	
	$row = elgg_format_element('td', ['class' => 'log-entry-time'], date('r', $entry->time_created));
	$row .= elgg_format_element('td', ['class' => 'log-entry-ip-address'], $entry->ip_address ?: '&nbsp;');
	$row .= elgg_format_element('td', ['class' => 'log-entry-user'], $user_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-guid'], $user_guid_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-object'], $object_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-guid'], $object_id_link);
	$row .= elgg_format_element('td', ['class' => 'log-entry-action'], $entry->event);
	
	echo elgg_format_element('tr', [], $row);
}
?>
</table>
<?php
