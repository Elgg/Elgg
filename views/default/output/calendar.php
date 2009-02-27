<?php

	/**
	 * Elgg calendar output
	 * Displays a calendar output field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * 
	 */

    if (is_int($vars['value'])) {
        echo date("F j, Y", $vars['value']);
    } else {
        echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');
    }

?>