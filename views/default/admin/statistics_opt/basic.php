<?php
	/**
	 * Elgg statistics screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Work out number of users
	$users_stats = get_number_users();
	
	$users_online = get_number_online();
	
	global $CONFIG;
	
	include_once($CONFIG->path . "version.php");
?>
<div>
    <h2><?php echo elgg_echo('admin:statistics:label:basic'); ?></h2>
    <table>
    	<tr>
            <td><b><?php echo elgg_echo('admin:statistics:label:version'); ?> :</b></td>
            <td><?php echo elgg_echo('admin:statistics:label:version:release'); ?> - <?php echo $release; ?>, <?php echo elgg_echo('admin:statistics:label:version:version'); ?> - <?php echo $version; ?></td>
        </tr>
        <tr>
            <td><b><?php echo elgg_echo('admin:statistics:label:numusers'); ?> :</b></td>
            <td><?php echo $users_stats; ?></td>
        </tr>

    </table> 
</div>  