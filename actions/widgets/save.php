<?php

	/**
	 * Elgg widget save action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		action_gatekeeper();
		
		$guid = get_input('guid');
		$params = $_REQUEST['params'];
		$pageurl = get_input('pageurl');
		$noforward = get_input('noforward',false);

		$result = false;
		
		if (!empty($guid)) {
			
			$result = save_widget_info($guid,$params);
			
		}
		
		if ($result) {
			system_message(elgg_echo('widgets:save:success'));
		} else {
			register_error(elgg_echo('widgets:save:failure'));
		}
		
		if (!$noforward)
			forward($_SERVER['HTTP_REFERER']);

?>