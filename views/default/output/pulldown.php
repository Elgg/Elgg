<?php

	/**
	 * Elgg pulldown display
	 * Displays a value that was entered into the system via a pulldown
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['text'] The text to display
	 * 
	 */

    echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); //$vars['value'];
?>