<?php
	/**
	 * Elgg JSON output
	 * This outputs the api as JSON
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */

	$result = $vars['result'];
	$export = $result->export();
	
	// echo json_encode($export);
	global $jsonexport;
	$jsonexport['api'][] = $export;
	
?>