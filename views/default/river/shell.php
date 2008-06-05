<?php
	/**
	 * Elgg river pageshell 
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/// Extract the river
	$river = $vars['river'];
	
	foreach ($river as $r)
		echo $r;
	
?>