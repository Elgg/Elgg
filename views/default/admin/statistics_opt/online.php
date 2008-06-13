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
	$users_online = get_number_online();
?>

<div>
    <h2><?php echo sprintf(elgg_echo('admin:statistics:label:onlineusers'), 10); ?></h2>
</div>

TODO: Writeme