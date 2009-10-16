<?php
/**
 * User settings for notifications.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $NOTIFICATION_HANDLERS;
$notification_settings = get_user_notification_settings(page_owner());

?>
<h3><?php echo elgg_echo('notifications:usersettings'); ?></h3>

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
	echo elgg_view('input/radio',array('internalname' => "method[{$k}]", 'options' => array(elgg_echo('option:yes') => 'yes',elgg_echo('option:no') => 'no'), 'value' => $val));

?>
			</td>
		</tr>
<?php
	}
?>
</table>