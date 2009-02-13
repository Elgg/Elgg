<?php

	/**
	 * Elgg administration user main screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Description of what's going on
		echo "<div class=\"contentWrapper\"><span class=\"contentIntro\">" . autop(elgg_echo("admin:user:description")) . "</span></div>";
	
		echo elgg_view("admin/user_opt/adduser");
		
		echo elgg_view("admin/user_opt/search");
		
		if ($vars['list']) echo $vars['list'];
		
?>