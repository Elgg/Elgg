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

	// users online
	get_context('search');
	$users_online = get_online_users();
	get_context('admin');
?>

<div>
    <h2><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h2>
    <?php echo $users_online; ?>
</div>
