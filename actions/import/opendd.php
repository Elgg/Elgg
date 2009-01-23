<?php
	/**
	 * Elgg OpenDD import action.
	 * 
	 * This action accepts data to import (in OpenDD format) and performs and import. It accepts 
	 * data as $data.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Safety
	admin_gatekeeper();
	action_gatekeeper();
	
	// Get input
	$data = get_input('data', '', false);
	
	// Import 
	$return = import($data);
	
	if ($return)
		system_message(elgg_echo('importsuccess'));
	else
		register_error(elgg_echo('importfail'));
		
	forward($_SERVER['HTTP_REFERER']);
?>