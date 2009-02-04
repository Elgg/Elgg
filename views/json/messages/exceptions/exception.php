<?php
	/**
	 * Elgg exception
	 * Displays a single exception
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An exception
	 */

	$export = $vars['object'];
	
	global $jsonexport;
	$jsonexport['exceptions'][] = $export;

?>