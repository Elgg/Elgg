<?php
	/**
	 * Elgg relationship export.
	 * Displays a relationship using JSON.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$r = $vars['relationship'];
	
	$export = new stdClass;

	$exportable_values = $entity->getExportableValues();
	
	foreach ($exportable_values as $v)
		$export->$v = $r->$v;
		
	global $jsonexport;
	$jsonexport['relationships'][] = $export;
	
?>