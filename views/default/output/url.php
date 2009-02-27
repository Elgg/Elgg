<?php

	/**
	 * Elgg URL display
	 * Displays a URL as a link
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The URL to display
	 * 
	 */

    $val = trim($vars['value']);
    if (!empty($val)) {
	    if ((substr_count($val, "http://") == 0) && (substr_count($val, "https://") == 0)) {
	        $val = "http://" . $val;
	    }
	    echo "<a href=\"{$val}\" target=\"_blank\">". htmlentities($val, ENT_QUOTES, 'UTF-8'). "</a>";
    }

?>