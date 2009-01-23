<?php
	/**
	 * Elgg metadata export.
	 * Displays a metadata item using PHP serialised data
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
	$exportable_values = $m->getExportableValues();
	
	foreach ($exportable_values as $v)
		$export->$v = $m->$v;
		
	echo serialize($export);
?>