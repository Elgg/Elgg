<?php
/**
 * Elgg statistics screen showing online users.
 *
 * @package Elgg
 * @subpackage Core
 */

$user = get_loggedin_user();

$logged_in = 0;
$log = get_system_log($user->guid, "login", "", 'user', '', 1);

if ($log) {
	$logged_in = $log[0]->time_created;
}

?>
<div class="user-settings details">
	<h3><?php echo elgg_echo('usersettings:statistics:yourdetails'); ?></h3>

	<table class="styled">
		<tr class="odd"><td class="column-one"><?php echo elgg_echo('usersettings:statistics:label:name'); ?></td><td><?php echo $user->name; ?></td></tr>
		<tr class="even"><td class="column-one"><?php echo elgg_echo('usersettings:statistics:label:email'); ?></td><td><?php echo $user->email; ?></td></tr>
		<tr class="odd"><td class="column-one"><?php echo elgg_echo('usersettings:statistics:label:membersince'); ?></td><td><?php echo date("r",$user->time_created); ?></td></tr>
		<tr class="even"><td class="column-one"><?php echo elgg_echo('usersettings:statistics:label:lastlogin'); ?></td><td><?php echo date("r",$logged_in); ?></td></tr>
	</table>
</div>