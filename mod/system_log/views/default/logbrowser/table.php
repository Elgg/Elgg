<?php
/**
 * Log browser table
 *
 * @package ElggLogBrowser
 */

$log_entries = elgg_extract('log_entries', $vars);

if (empty($log_entries)) {
	echo elgg_echo('notfound');
	return true;
}

?>

<table class="elgg-table">
	<tr>
		<th><?php echo elgg_echo('logbrowser:date'); ?></th>
		<th><?php echo elgg_echo('logbrowser:ip_address'); ?></th>
		<th><?php echo elgg_echo('logbrowser:user:name'); ?></th>
		<th><?php echo elgg_echo('logbrowser:user:guid'); ?></th>
		<th><?php echo elgg_echo('logbrowser:object'); ?></th>
		<th><?php echo elgg_echo('logbrowser:object:guid'); ?></th>
		<th><?php echo elgg_echo('logbrowser:action'); ?></th>
	</tr>
<?php
	$alt = '';
foreach ($log_entries as $entry) {
	if ($entry->ip_address) {
		$ip_address = $entry->ip_address;
	} else {
		$ip_address = '&nbsp;';
	}

	$user = get_entity($entry->performed_by_guid);
	if ($user) {
		$user_link = elgg_view('output/url', [
			'href' => $user->getURL(),
			'text' => $user->getDisplayName(),
			'is_trusted' => true,
		]);
		$user_guid_link = elgg_view('output/url', [
			'href' => "admin/administer_utilities/logbrowser?user_guid={$user->guid}",
			'text' => $user->getGUID(),
			'is_trusted' => true,
		]);
	} else {
		$user_guid_link = $user_link = '&nbsp;';
	}

	$object = system_log_get_object_from_log_entry($entry);
	if (is_callable([$object, 'getURL'])) {
		$object_link = elgg_view('output/url', [
			'href' => $object->getURL(),
			'text' => $entry->object_class,
			'is_trusted' => true,
		]);
	} else {
		$object_link = $entry->object_class;
	}
?>
<tr <?php echo $alt; ?>>
<td class="log-entry-time">
	<?php echo date('r', $entry->time_created); ?>
</td>
<td class="log-entry-ip-address">
	<?php echo $ip_address; ?>
</td>
<td class="log-entry-user">
	<?php echo $user_link; ?>
</td>
<td class="log-entry-guid">
	<?php echo $user_guid_link; ?>
</td>
<td class="log-entry-object">
	<?php echo $object_link; ?>
</td>
<td class="log-entry-guid">
	<?php echo $entry->object_id; ?>
</td>
<td class="log-entry-action">
	<?php echo elgg_echo($entry->event); ?>
</td>
</tr>
<?php

$alt = $alt ? '' : 'class="alt"';
}
?>
</table>
<?php
