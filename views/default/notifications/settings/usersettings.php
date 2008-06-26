<?php
	/**
	 * User settings for notifications.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	global $NOTIFICATION_HANDLERS;
	$notification_settings = get_user_notification_settings();
?>
	<h2><?php echo elgg_echo('notifications:usersettings'); ?></h2>
	
	<p><?php echo elgg_echo('notifications:methods'); ?>
	
	<table>
<?php
		// Loop through options
		foreach ($NOTIFICATION_HANDLERS as $k => $v) 
		{	
?>
			<tr>
				<td><?php echo $k; ?>: </td>

				<td>
					<input type="radio" name="method[<?php echo $k; ?>]" value="yes" <?php if ($notification_settings->$k) echo "checked=\"yes\" "; ?>/><?php echo elgg_echo("yes"); ?><br />
					<input type="radio" name="method[<?php echo $k; ?>]" value="no" <?php if (!$notification_settings->$k) echo "checked=\"yes\" "; ?>/><?php echo elgg_echo("no"); ?>
				</td>
			</tr>
<?php
		}
?>
	</table>