<?php

	/**
	 * Elgg widget reorder action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */


		$owner = get_input('owner');
		$context = get_input('context');
		
		$maincontent = get_input('debugField1');
		$sidebar = get_input('debugField2');
		$rightbar = get_input('debugField3');
		
		$result = reorder_widgets_from_panel($maincontent, $sidebar, $rightbar, $context, $owner);
		
		if ($result) {
			system_message(elgg_echo('widgets:panel:save:success'));
		} else {
			register_error(elgg_echo('widgets:panel:save:failure'));
		}
		
		forward($_SERVER['HTTP_REFERER']);

?>