<?php
	/**
	 * Elgg relationship export.
	 * Displays a relationship using ODD.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$r = $vars['relationship'];
	
	//$odd = new ODDDocument();
	//$odd->addElement($r->export());
	
	//echo $odd;
	
	echo $r->export();
?>