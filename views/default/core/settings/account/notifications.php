<?php
/**
 * User settings for notifications.
 *
 * @package Elgg
 * @subpackage Core
 */

global $NOTIFICATION_HANDLERS;
$notification_settings = get_user_notification_settings(elgg_get_page_owner_guid());

?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('notifications:usersettings'); ?></h3>
	</div>
	<div class="elgg-body">

<p><?php echo elgg_echo('notifications:methods'); ?>

<table>
<?php
	// Loop through options
	foreach ($NOTIFICATION_HANDLERS as $k => $v) {
?>
		<tr>
			<td><?php echo elgg_echo($k); ?>: </td>

			<td>
<?php

	if ($notification_settings->$k) {
		$val = "yes";
	} else {
		$val = "no";
	}
	
	echo elgg_view('input/radio', array(
		'name' => "method[$k]",
		'value' => $val,
		'options' => array(
			elgg_echo('option:yes') => 'yes',
			elgg_echo('option:no') => 'no'
		),
	));

?>
			</td>
		</tr>
<?php
	}
?>
</table>
	</div>
</div>