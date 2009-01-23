<?php

    /**
	 * Elgg sample welcome page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	 
	if (isloggedin()) {
		echo elgg_view("welcome/logged_in");		
	} else {
	 	echo elgg_view("welcome/logged_out");
	}
	 
?>