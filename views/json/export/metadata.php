<?php
	/**
	 * Elgg metadata export.
	 * Displays a metadata item using json
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$m = $vars['metadata'];
	
	$export = new stdClass;
	$exportable_values = $entity->getExportableValues();
	
	foreach ($exportable_values as $v)
		$export->$v = $m->$v;
		
	global $jsonexport;
	$jsonexport['metadata'][] = $entity;
	// echo json_encode($export);
?>