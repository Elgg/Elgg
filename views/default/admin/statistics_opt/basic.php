<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

global $CONFIG;

// Get version information
$version = get_version();
$release = get_version(true);

?>
<div class="admin_statistics">
	<h3><?php echo elgg_echo('admin:statistics:label:basic'); ?></h3>
	<table>
		<tr class="odd">
			<td class="column_one"><b><?php echo elgg_echo('admin:statistics:label:version'); ?> :</b></td>
			<td><?php echo elgg_echo('admin:statistics:label:version:release'); ?> - <?php echo $release; ?>, <?php echo elgg_echo('admin:statistics:label:version:version'); ?> - <?php echo $version; ?></td>
		</tr>
		<tr class="even">
			<td class="column_one"><b><?php echo elgg_echo('admin:statistics:label:numusers'); ?> :</b></td>
			<td><?php echo $users_stats; ?> <?php echo elgg_echo('active'); ?> / <?php echo $total_users; ?> <?php echo elgg_echo('total') ?></td>
		</tr>

	</table>
</div>