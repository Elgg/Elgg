<?php

    /**
	 * Elgg action handler
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
    /**
     *  Load searunner framework
     */
        require_once(dirname(__FILE__) . "/engine/start.php");
        $action = get_input("action");
        action($action);
    
?>
