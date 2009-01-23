<?php
	/**
	 * Elgg metadata export.
	 * Displays a metadata item using the current view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$m = $vars['metadata'];
	$uuid = $vars['uuid'];
	
	//$odd = new ODDDocument();
	//$odd->addElement($m->export());
	
	//echo $odd;
	
	echo $m->export();
?>