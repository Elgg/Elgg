<?php
	/**
	 * Elgg PHP output
	 * This outputs the api as PHP
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
	
	echo serialize($export);
?>