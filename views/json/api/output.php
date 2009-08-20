<?php
	/**
	 * Elgg JSON output
	 * This outputs the api as JSON
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 * 
	 */

	$result = $vars['result'];
	$export = $result->export();
	
	// echo json_encode($export);
	global $jsonexport;
	$jsonexport['api'][] = $export;
	
?>