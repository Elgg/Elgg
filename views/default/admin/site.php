<?php

	/**
	 * Elgg administration site main screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	global $CONFIG;
	
	// Description of what's going on
		echo "<div class=\"contentWrapper\">" . autop(elgg_echo("admin:site:description")) . " ";
	
		echo elgg_view("settings/system",array("action" => $CONFIG->wwwroot."action/admin/site/update_basic")); // Always want to do this first.
		
		echo "</div>";
?>