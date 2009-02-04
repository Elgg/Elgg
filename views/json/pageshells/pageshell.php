<?php
	/**
	 * Elgg JSON output pageshell
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */

	if(stristr($_SERVER["HTTP_ACCEPT"],"application/json")) { 
		header("Content-Type: application/json");		
	} else {
		header("Content-Type: application/javascript");
	}
	// echo $vars['body'];
	
	global $jsonexport;
	echo json_encode($jsonexport);
?>