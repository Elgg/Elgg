<?php

	/**
	 * Elgg JS pageshell
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$body = $vars['body'];
	
	// Remove excess carriage returns
		$body = str_replace("\r",'',$body);

		$body = explode("\n",$body);
		
		foreach($body as $line) {
			
			echo "document.write('" . addslashes($line) . "');\n";
			
		}
		
?>