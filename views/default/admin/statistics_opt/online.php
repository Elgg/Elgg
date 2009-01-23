<?php
	/**
	 * Elgg statistics screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// users online
	get_context('search');
	$users_online = get_online_users();
	get_context('admin');
?>

<div class="admin_users_online">
    <h3><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h3>
    <?php echo $users_online; ?>
</div>
