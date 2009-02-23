<?php

    /**
	 * Elgg action handler
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
    /**
     *  Load Elgg framework
     */
		define('externalpage',true);
        require_once("../start.php");
        $action = get_input("action");
        action($action);
    
?>
