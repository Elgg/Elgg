<?php

	/**
	 * Elgg CSS file
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	    header("Content-type: text/css");
	
	    require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
	    
	    $default_css = elgg_view("css");
	    
	    echo $default_css;
    
?>