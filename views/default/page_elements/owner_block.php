<?php

	/**
	 * Elgg owner block
	 * Displays page ownership information
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */

		echo "<div id=\"owner_block\">";
	// Is there a page owner?
		if ($owner = page_owner_entity()) {
			$icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
			if ($owner instanceof ElggUser || $owner instanceof ElggGroup) {
				$info = $owner->name;
			}
			$display = "<div id=\"owner_block_icon\">" . $icon . "</div>";
			$display .= "<div id=\"owner_block_content\">" . $info . "</div>";
		}
		echo $display;
		
	// Have we been asked to inject any content? If so, display it
		if (isset($vars['content']))
			echo $vars['content'];
		
	// Initialise the submenu
		$submenu = get_submenu(); // elgg_view('canvas_header/submenu');
		if (!empty($submenu)) $submenu = "<ul>" . $submenu . "</ul>";
		if (!empty($submenu))
			echo "<div id=\"owner_block_submenu\">" . $submenu . "</div>"; // plugins can extend this to add menu options
			
		echo "</div>";

?>