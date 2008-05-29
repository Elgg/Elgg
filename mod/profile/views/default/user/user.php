<?php

	/**
	 * Elgg user display
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

		echo elgg_view_layout("one_column",elgg_view("profile/userdetails",$vars),elgg_view("profile/menu",$vars));
	
?>