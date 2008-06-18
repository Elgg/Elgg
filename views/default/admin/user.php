<?php

	/**
	 * Elgg administration user main screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Description of what's going on
		echo "<p>" . nl2br(elgg_echo("admin:user:description")) . "</p>";
	
		echo elgg_view("admin/user_opt/search");
		
		if ($vars['list']) echo $vars['list'];
		
?>