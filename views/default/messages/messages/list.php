<?php

	/**
	 * Elgg list system messages
	 * Lists system messages
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An array of system messages
	 */

?>

	<div class="messages">
		<div class="messages-errors">

<?php
		if (!empty($vars['object']) && is_array($vars['object'])) {
			foreach($vars['object'] as $message) {
				echo elgg_view('messages/messages/message',array('object' => $message));
			}
		}

?>
		</div>
	</div>