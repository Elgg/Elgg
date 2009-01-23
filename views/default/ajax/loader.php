<?php

	/**
	 * Elgg AJAX loader
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$loader = <<< END
	
	<div align="center" class="ajax_loader"></div>
	
END;

	$loader = str_replace("\n","",$loader);
	$loader = str_replace("\r","",$loader);

	if (isset($vars['slashes']) && $vars['slashes'] == true) {
		$loader = addslashes($loader);
	}
	
	echo $loader;
		
?>