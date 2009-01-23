<?php
	/**
	 * Provide a way of setting your full name.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$user = page_owner_entity();
	
	if ($user) {
?>
	<h3><?php echo elgg_echo('user:set:name'); ?></h3>
	<p>
		<?php echo elgg_echo('user:name:label'); ?>:
		<?php

			echo elgg_view('input/text',array('internalname' => 'name', 'value' => $user->name));
			echo elgg_view('input/hidden',array('internalname' => 'guid', 'value' => $user->guid));
		?> 
	</p>

<?php } ?>