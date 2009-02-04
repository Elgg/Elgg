<?php
	/**
	 * Elgg Entity export.
	 * Displays an entity as JSON
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$entity = $vars['entity'];
	
	$export = new stdClass;
	$exportable_values = $entity->getExportableValues();
	
	foreach ($exportable_values as $v)
		$export->$v = $entity->$v;
		
	$export->url = $entity->getURL();
		
	global $jsonexport;
	$jsonexport[$entity->getType()][$entity->getSubtype()][] = $export;
?>