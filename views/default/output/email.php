<?php

	/**
	 * Elgg email output
	 * Displays an email address that was entered using an email input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The email address to display
	 * 
	 */

    if (!empty($vars['value'])) {
    	echo "<a href=\"mailto:" . $vars['value'] . "\">". htmlentities($vars['value'], ENT_QUOTES, 'UTF-8') ."</a>";
    }
?>