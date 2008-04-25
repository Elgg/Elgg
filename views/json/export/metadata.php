<?php
	/**
	 * Elgg metadata export.
	 * Displays a metadata item using json
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$m = $vars['metadata'];
	
	$export = new stdClass;
	
	foreach ($m as $k => $v)
		$export->$k = $v;
		
	echo json_encode($export);
?>